<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\User;
use App\Services\MemberManagementService;
use Illuminate\Http\Request;

class MemberManagementController extends Controller
{
    protected MemberManagementService $memberManagementService;

    public function __construct(MemberManagementService $memberManagementService)
    {
        $this->memberManagementService = $memberManagementService;
    }

    public function index()
    {
        if (!auth()->user()->hasPermission('member-management-menu')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            return $this->memberManagementService->datatable();
        }

        $tours = Tour::whereIn('status', ['upcoming', 'ongoing'])->get();
        $members = User::where('user_type', User::NORMAL_USER_CODE)->get();

        return view('admin.member-management.index', compact('tours', 'members'));
    }

    public function addToTour(Request $request)
    {
        if (!auth()->user()->hasPermission('member-management-create')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'exists:tours,id'],
                'user_id' => ['required', 'exists:users,id'],
                'room_no' => ['nullable', 'string', 'max:50'],
                'seat_no' => ['nullable', 'string', 'max:50'],
                'join_status' => ['required', 'in:pending,approved,cancelled,completed'],
            ]);

            $this->memberManagementService->store($validatedData);

            return redirect()
                ->route('member-management.index')
                ->with([
                    'message' => 'Member added to tour successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to add member to tour. ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasPermission('member-management-update')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $validatedData = $request->validate([
                'room_no' => ['nullable', 'string', 'max:50'],
                'seat_no' => ['nullable', 'string', 'max:50'],
                'join_status' => ['required', 'in:pending,approved,cancelled,completed'],
            ]);

            $this->memberManagementService->update($id, $validatedData);

            return redirect()
                ->route('member-management.index')
                ->with([
                    'message' => 'Member updated successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update member. ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('member-management-delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $this->memberManagementService->delete($id);

            return redirect()
                ->route('member-management.index')
                ->with([
                    'message' => 'Member removed from tour successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to remove member from tour.']);
        }
    }
}
