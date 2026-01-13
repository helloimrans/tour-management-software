<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        return view('admin.roles.browse');
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('roles-create')) {
            abort(403, 'Unauthorized action.');
        }
        $role = new Role();
        return view('admin.roles.edit-add', compact('role'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('roles-create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'display_name' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:191', 'unique:roles,name'],
                'description' => ['nullable', 'string'],
            ]);

            $this->roleService->createRole($validatedData);

            return redirect()
                ->route('roles.index')
                ->with([
                    'message' => 'Role created successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to create role. Please try again.'])
                ->withInput();
        }
    }

    public function show(Role $role)
    {
        if (!auth()->user()->hasPermission('roles-read')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.roles.read', compact('role'));
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->hasPermission('roles-update')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.roles.edit-add', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('roles-update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'display_name' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:191', 'unique:roles,name,' . $role->id],
                'description' => ['nullable', 'string'],
            ]);

            $this->roleService->updateRole($role, $validatedData);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Role updated successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update role. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->hasPermission('roles-delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $this->roleService->deleteRole($role);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Role deleted successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete role. Please try again.']);
        }
    }

    public function getDatatable(Request $request)
    {
        return $this->roleService->getListDataForDatatable($request);
    }

    public function rolePermissionIndex(Role $role)
    {
        if (!auth()->user()->hasPermission('roles-change-permission')) {
            abort(403, 'Unauthorized action.');
        }
        $permissionsGroupByTable = Permission::all()->groupBy('group_name');
        return view('admin.roles.permissions', compact('role', 'permissionsGroupByTable'));
    }

    public function rolePermissionSync(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('roles-change-permission')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $permissions = $request->input('permissions', []);
            $this->roleService->syncRolePermission($role, $permissions);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Permissions updated successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update permissions. Please try again.']);
        }
    }
}
