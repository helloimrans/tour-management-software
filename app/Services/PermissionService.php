<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionService
{
    public function createPermission(array $postData)
    {
        return Permission::create($postData);
    }

    public function updatePermission(Permission $permission, array $postData)
    {
        return $permission->update($postData);
    }

    public function deletePermission(Permission $permission)
    {
        return $permission->delete();
    }

    public function getListDataForDatatable($request)
    {
        $authUser = AuthHelper::getAuthUser();

        $permissions = Permission::select([
            'permissions.id as id',
            'permissions.name',
            'permissions.display_name',
            'permissions.created_at',
            'permissions.updated_at'
        ]);

        return DataTables::eloquent($permissions)
            ->addColumn('action', function (Permission $permission) use ($authUser) {
                $actions = '';

                if ($authUser->hasPermission('permissions-read')) {
                    $actions .= '<a href="' . route('permissions.show', $permission->id) . '" class="btn btn-info btn-xs mx-1">
                        <i class="fas fa-eye"></i> View
                    </a>';
                }

                if ($authUser->hasPermission('permissions-update')) {
                    $actions .= '<a href="' . route('permissions.edit', $permission->id) . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('permissions-delete')) {
                    $actions .= '<a href="#" data-action="' . route('permissions.destroy', $permission->id) . '" class="btn bg-gradient-danger btn-xs mx-1 delete">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
