<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    protected TourService $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->tourService->datatable();
        }

        return view('admin.tour.index');
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('tour-create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.tour.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('tour-create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191'],
                'destination' => ['required', 'string', 'max:191'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'description' => ['nullable', 'string'],
                'total_cost' => ['nullable', 'numeric', 'min:0'],
                'per_member_cost' => ['nullable', 'numeric', 'min:0'],
                'max_members' => ['required', 'integer', 'min:1'],
                'status' => ['required', 'in:upcoming,ongoing,completed,closed'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            ]);

            $this->tourService->store($validatedData);

            return redirect()
                ->route('tour.index')
                ->with([
                    'message' => 'Tour created successfully.',
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
                ->withErrors(['error' => 'Failed to create tour. Please try again.'])
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        if (!auth()->user()->hasPermission('tour-update')) {
            abort(403, 'Unauthorized action.');
        }
        $data = [
            'data' => $this->tourService->show($id),
        ];

        return view('admin.tour.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasPermission('tour-update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191'],
                'destination' => ['required', 'string', 'max:191'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'description' => ['nullable', 'string'],
                'total_cost' => ['nullable', 'numeric', 'min:0'],
                'per_member_cost' => ['nullable', 'numeric', 'min:0'],
                'max_members' => ['required', 'integer', 'min:1'],
                'status' => ['required', 'in:upcoming,ongoing,completed,closed'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            ]);

            $this->tourService->update($id, $validatedData);

            return redirect()
                ->route('tour.index')
                ->with([
                    'message' => 'Tour updated successfully.',
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
                ->withErrors(['error' => 'Failed to update tour. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('tour-delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $this->tourService->delete($id);

            return redirect()
                ->route('tour.index')
                ->with([
                    'message' => 'Tour deleted successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete tour. Please try again.']);
        }
    }
}
