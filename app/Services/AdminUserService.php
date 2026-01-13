<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminUserService
{
    public function validator(array $data, ?int $id = null): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['nullable', 'string', 'max:191'],
            'phone' => [
                'required',
                'regex:/^(01[3-9]\d{8})$/',
                'unique:users,phone,' . $id,
            ],
            'email' => [
                'required',
                'email',
                'max:191',
                'unique:users,email,' . $id,
            ],
            'profile_pic' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp,svg,gif',
                'max:5120',
            ],
            'password' => $id
                ? ['nullable', 'string', 'min:5']
                : ['required', 'string', 'min:5'],
            'role_id' => ['required', 'array', 'min:1'],
            'role_id.*' => ['required', 'integer', 'exists:roles,id'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll()
    {
        return User::with(['createdBy', 'updatedBy'])
            ->where('user_type', User::ADMIN_USER_CODE)
            ->latest()
            ->get();
    }

    public function store(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['user_type'] = User::ADMIN_USER_CODE;
        $input['created_by'] = Auth::id();

        $roles = $input['role_id'];
        unset($input['role_id']);

        $user = User::create($input);
        $user->syncRoles($roles);

        return $user;
    }

    public function show(int $id): User
    {
        return User::with(['roles'])
            ->where('user_type', User::ADMIN_USER_CODE)
            ->findOrFail($id);
    }

    public function update(int $id, array $input): User
    {
        $user = User::where('user_type', User::ADMIN_USER_CODE)
            ->findOrFail($id);

        if (isset($input['password']) && !empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        if (isset($input['profile_pic'])) {
            if ($user->profile_pic) {
                deleteFile($user->profile_pic);
            }
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        $input['updated_by'] = Auth::id();

        $roles = $input['role_id'] ?? [];
        unset($input['role_id']);

        $user->update($input);
        $user->syncRoles($roles);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = User::where('user_type', User::ADMIN_USER_CODE)
            ->findOrFail($id);

        if ($user->profile_pic) {
            deleteFile($user->profile_pic);
        }

        $user->deleted_by = Auth::id();
        $user->save();
        $user->delete();

        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['createdBy', 'updatedBy', 'roles'])
            ->adminUser()
            ->latest();

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('display_name')->implode(', ') ?: '-';
            })
            ->editColumn('profile_pic', function ($row) {
                $imageUrl = $row->profile_pic
                    ? Storage::url($row->profile_pic)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Profile" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('status', function ($row) use ($authUser) {
                if (!$authUser->hasPermission('admin-user-change-status')) {
                    return '-';
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

                if ($authUser->hasPermission('admin-user-update')) {
                    $editUrl = route('admin.user.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('admin-user-delete')) {
                    $deleteUrl = route('admin.user.destroy', $row->id);
                    $formId = 'delForm-' . $row->id;

                    $actions .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="btn bg-gradient-danger btn-xs mx-1"
                                onclick="confirmDelete(\'' . $formId . '\')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action', 'status', 'profile_pic'])
            ->make(true);
    }
}
