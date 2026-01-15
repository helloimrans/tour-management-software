<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TourService
{
    public function getAll()
    {
        return Tour::with(['createdBy', 'updatedBy'])->latest()->get();
    }

    public function store(array $input): Tour
    {
        if (isset($input['image'])) {
            $input['image'] = uploadFile($input['image'], 'tours');
        }

        $input['created_by'] = Auth::id();

        return Tour::create($input);
    }

    public function show(int $id): Tour
    {
        return Tour::findOrFail($id);
    }

    public function update(int $id, array $input): Tour
    {
        $tour = Tour::findOrFail($id);

        if (isset($input['image'])) {
            if ($tour->image) {
                deleteFile($tour->image);
            }
            $input['image'] = uploadFile($input['image'], 'tours');
        }

        $input['updated_by'] = Auth::id();

        $tour->update($input);

        return $tour->fresh();
    }

    public function delete(int $id): bool
    {
        $tour = Tour::findOrFail($id);

        if ($tour->image) {
            deleteFile($tour->image);
        }

        $tour->deleted_by = Auth::id();
        $tour->save();
        $tour->delete();

        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Tour::with(['createdBy', 'updatedBy'])->latest();

        return DataTables::of($data)
            ->addColumn('destination', function ($row) {
                return $row->destination ?? '-';
            })
            ->addColumn('dates', function ($row) {
                return $row->start_date->format('d M Y') . ' - ' . $row->end_date->format('d M Y');
            })
            ->addColumn('cost', function ($row) {
                return '৳' . number_format($row->per_member_cost, 2);
            })
            ->addColumn('members', function ($row) {
                $current = $row->tourMembers()->where('join_status', 'approved')->count();
                return $current . ' / ' . $row->max_members;
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->editColumn('image', function ($row) {
                $imageUrl = $row->image
                    ? Storage::url($row->image)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Tour" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('status', function ($row) {
                $statusClass = [
                    'upcoming' => 'badge-info',
                    'ongoing' => 'badge-success',
                    'completed' => 'badge-secondary',
                    'closed' => 'badge-danger',
                ];
                
                $class = $statusClass[$row->status] ?? 'badge-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                // Schedule button - only show if user has permission
                if ($authUser->hasPermission('tour-schedule-menu')) {
                    $scheduleUrl = route('tour.schedule.index', $row->id);
                    $actions .= '<a href="' . $scheduleUrl . '" class="btn bg-gradient-info btn-xs mx-1" title="Manage Schedule">
                        <i class="fa-solid fa-calendar-days"></i> Schedule
                    </a>';
                }

                if ($authUser->hasPermission('tour-update')) {
                    $editUrl = route('tour.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('tour-delete')) {
                    $deleteUrl = route('tour.destroy', $row->id);
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
            ->rawColumns(['action', 'status', 'image', 'dates', 'cost', 'members'])
            ->make(true);
    }
}
