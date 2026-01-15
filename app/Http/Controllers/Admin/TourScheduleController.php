<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Services\TourScheduleService;
use Illuminate\Http\Request;

class TourScheduleController extends Controller
{
    protected TourScheduleService $tourScheduleService;

    public function __construct(TourScheduleService $tourScheduleService)
    {
        $this->tourScheduleService = $tourScheduleService;
    }

    public function index($tourId)
    {
        if (!auth()->user()->hasPermission('tour-schedule-menu')) {
            abort(403, 'Unauthorized action.');
        }

        $tour = Tour::findOrFail($tourId);

        if (request()->ajax()) {
            return $this->tourScheduleService->datatable($tourId);
        }

        return view('admin.tour-schedule.index', compact('tour'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('tour-schedule-create')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'exists:tours,id'],
                'schedule_date' => ['required', 'date'],
                'title' => ['required', 'string', 'max:191'],
                'details' => ['nullable', 'string'],
            ]);

            $this->tourScheduleService->store($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Schedule added successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add schedule.',
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasPermission('tour-schedule-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'exists:tours,id'],
                'schedule_date' => ['required', 'date'],
                'title' => ['required', 'string', 'max:191'],
                'details' => ['nullable', 'string'],
            ]);

            $this->tourScheduleService->update($id, $validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule.',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('tour-schedule-delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $this->tourScheduleService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule.',
            ], 500);
        }
    }
}
