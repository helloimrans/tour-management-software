<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    public function createRole(array $postData)
    {
        return Role::create($postData);
    }

    public function updateRole(Role $role, array $postData)
    {
        return $role->update($postData);
    }

    public function deleteRole(Role $role)
    {
        return $role->delete();
    }

    public function syncRolePermission(Role $role, array $permissions)
    {
        $role->permissions()->sync($permissions);

        $role->users()->pluck('id')->each(function ($userId) {
            Cache::forget('userwise_permissions_' . $userId);
        });

        return $role;
    }

    public function getListDataForDatatable($request)
    {
        $authUser = AuthHelper::getAuthUser();

        $roles = Role::select([
            'roles.id as id',
            'roles.name',
            'roles.display_name',
            'roles.description',
            'roles.created_at',
            'roles.updated_at',
        ]);

        return DataTables::eloquent($roles)
            ->editColumn('description', function (Role $role) {
                return \Illuminate\Support\Str::limit($role->description, 20, '...');
            })
            ->addColumn('action', function (Role $role) use ($authUser) {
                $actions = '';

                if ($authUser->hasPermission('roles-change-permission')) {
                    $actions .= '<a href="' . route('roles.permissions', $role->id) . '" class="btn btn-primary btn-xs mx-1">
                        <i class="fa-solid fa-key"></i> Permissions
                    </a>';
                }

                if ($authUser->hasPermission('roles-read')) {
                    $actions .= '<a href="' . route('roles.show', $role->id) . '" class="btn btn-info btn-xs mx-1">
                        <i class="fas fa-eye"></i> View
                    </a>';
                }

                if ($authUser->hasPermission('roles-update')) {
                    $actions .= '<a href="' . route('roles.edit', $role->id) . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('roles-delete')) {
                    $actions .= '<a href="#" data-action="' . route('roles.destroy', $role->id) . '" class="btn bg-gradient-danger btn-xs mx-1 delete">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
