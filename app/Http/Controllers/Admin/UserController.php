<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->userService->datatable();
        }

        return view('admin.user.index');
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('general-user-create')) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'roles' => \App\Models\Role::where('name', '!=', 'admin')->get(),
        ];

        return view('admin.user.create', $data);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        if (!auth()->user()->hasPermission('general-user-create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['nullable', 'string', 'max:191'],
                'phone' => [
                    'required',
                    'regex:/^(01[3-9]\d{8})$/',
                    'unique:users,phone',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:191',
                    'unique:users,email',
                ],
                'profile_pic' => [
                    'nullable',
                    'mimes:jpg,jpeg,png,webp,svg,gif',
                    'max:5120',
                ],
                'password' => ['required', 'string', 'min:5'],
                'address' => ['nullable', 'string', 'max:500'],
                'role_id' => ['required', 'array', 'min:1'],
                'role_id.*' => ['required', 'integer', 'exists:roles,id'],
            ], [
                'first_name.required' => 'First name is required.',
                'phone.required' => 'Phone number is required.',
                'phone.regex' => 'Please enter a valid phone number (01XXXXXXXXX).',
                'phone.unique' => 'This phone number is already registered.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 5 characters.',
                'role_id.required' => 'Please select at least one role.',
                'role_id.min' => 'Please select at least one role.',
            ]);

            $this->userService->store($validatedData);

            return redirect()
                ->route('general.user.index')
                ->with([
                    'message' => 'Member created successfully.',
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
                ->withErrors(['error' => 'Failed to create member. Please try again.'])
                ->withInput();
        }
    }

    public function approve($id)
    {
        if (!auth()->user()->hasPermission('general-user-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $request = request();
            $request->validate([
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['required', 'integer', 'exists:roles,id'],
            ], [
                'roles.required' => 'Please select at least one role.',
                'roles.min' => 'Please select at least one role.',
            ]);

            $user = \App\Models\User::findOrFail($id);

            if ($user->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already approved.',
                ], 400);
            }

            // Approve user and assign roles
            $user->status = 1;
            $user->updated_by = auth()->id();
            $user->save();

            $roles = $request->input('roles', []);
            $user->syncRoles($roles);

            return response()->json([
                'success' => true,
                'message' => 'Member approved successfully with role assignment.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve member: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function assignRole($id)
    {
        if (!auth()->user()->hasPermission('general-user-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $roles = request('roles', []);
            $user = \App\Models\User::findOrFail($id);
            $user->syncRoles($roles);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role.',
            ], 500);
        }
    }
}
