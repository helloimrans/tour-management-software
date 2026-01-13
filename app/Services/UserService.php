<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['createdBy', 'updatedBy', 'roles'])->generalUser()->latest();

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->editColumn('profile_pic', function ($row) {
                $imageUrl = $row->profile_pic
                    ? Storage::url($row->profile_pic)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Profile" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('status', function ($row) use ($authUser) {
                if (!$authUser || !$authUser->hasPermission('general-user-change-status')) {
                    if ($row->status == 0) {
                        return '<span class="badge badge-warning">Pending Approval</span>';
                    }
                    return $row->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                }

                $checked = $row->status ? 'checked' : '';
                $switchId = 'customSwitch' . $row->id;

                return '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input change-status-checkbox"
                           id="' . $switchId . '" data-id="' . $row->id . '" data-table="users" data-column="status" ' . $checked . '>
                    <label class="custom-control-label" for="' . $switchId . '"></label>
                </div>';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                // Show approve button for inactive users (status = 0)
                if ($row->status == 0 && $authUser && $authUser->hasPermission('general-user-update')) {
                    $actions .= '<button class="btn bg-gradient-success btn-xs mx-1 approve-member-btn"
                        data-id="' . $row->id . '"
                        data-name="' . $row->first_name . ' ' . ($row->last_name ?? '') . '">
                        <i class="fa-solid fa-check-circle"></i> Approve
                    </button>';
                }

                // Show assign role button for active users
                if ($row->status == 1 && $authUser && $authUser->hasPermission('general-user-update')) {
                    $actions .= '<button class="btn bg-gradient-primary btn-xs mx-1 edit-role-btn"
                        data-id="' . $row->id . '"
                        data-name="' . $row->first_name . ' ' . ($row->last_name ?? '') . '"
                        data-roles=\'' . $row->roles->pluck('id')->toJson() . '\'>
                        <i class="fa-solid fa-user-tag"></i> Assign Role
                    </button>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action', 'status', 'profile_pic'])
            ->make(true);
    }

    public function store(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = \Illuminate\Support\Facades\Hash::make($input['password']);
        }

        $input['user_type'] = User::NORMAL_USER_CODE;
        $input['status'] = 1; // Active by default when created by admin
        $input['created_by'] = auth()->id();
        $input['updated_by'] = auth()->id();

        $roles = $input['role_id'] ?? [];
        unset($input['role_id']);

        $user = User::create($input);

        if (!empty($roles)) {
            $user->syncRoles($roles);
        }

        return $user;
    }
}
