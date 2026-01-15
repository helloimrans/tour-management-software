<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\TourMember;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MemberManagementService
{
    public function getAll()
    {
        return TourMember::with(['tour', 'user', 'createdBy'])->latest()->get();
    }

    public function store(array $input): TourMember
    {
        $input['created_by'] = Auth::id();
        $input['joined_at'] = now();

        return TourMember::create($input);
    }

    public function show(int $id): TourMember
    {
        return TourMember::with(['tour', 'user'])->findOrFail($id);
    }

    public function update(int $id, array $input): TourMember
    {
        $tourMember = TourMember::findOrFail($id);
        $input['updated_by'] = Auth::id();
        $tourMember->update($input);

        return $tourMember->fresh();
    }

    public function delete(int $id): bool
    {
        $tourMember = TourMember::findOrFail($id);
        $tourMember->delete();

        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = TourMember::with(['tour', 'user', 'createdBy'])->latest();

        return DataTables::of($data)
            ->addColumn('member_name', function ($row) {
                return ($row->user->first_name ?? '') . ' ' . ($row->user->last_name ?? '');
            })
            ->addColumn('member_phone', function ($row) {
                return $row->user->phone ?? '-';
            })
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->addColumn('tour_destination', function ($row) {
                return $row->tour->destination ?? '-';
            })
            ->editColumn('join_status', function ($row) {
                $statusClass = [
                    'pending' => 'badge-warning',
                    'approved' => 'badge-success',
                    'cancelled' => 'badge-danger',
                    'completed' => 'badge-info',
                ];
                
                $class = $statusClass[$row->join_status] ?? 'badge-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($row->join_status) . '</span>';
            })
            ->addColumn('room_seat', function ($row) {
                $room = $row->room_no ? 'Room: ' . $row->room_no : '';
                $seat = $row->seat_no ? 'Seat: ' . $row->seat_no : '';
                return $room . ($room && $seat ? ', ' : '') . $seat ?: '-';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser && $authUser->hasPermission('member-management-update')) {
                    $actions .= '<button class="btn bg-gradient-primary btn-xs mx-1 edit-btn"
                        data-id="' . $row->id . '"
                        data-tour-id="' . $row->tour_id . '"
                        data-user-id="' . $row->user_id . '"
                        data-room-no="' . $row->room_no . '"
                        data-seat-no="' . $row->seat_no . '"
                        data-join-status="' . $row->join_status . '">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>';
                }

                if ($authUser && $authUser->hasPermission('member-management-delete')) {
                    $deleteUrl = route('member-management.destroy', $row->id);
                    $formId = 'delForm-' . $row->id;

                    $actions .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="btn bg-gradient-danger btn-xs mx-1"
                                onclick="confirmDelete(\'' . $formId . '\')">
                            <i class="fa-solid fa-trash"></i> Remove
                        </button>
                    </form>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action', 'join_status'])
            ->make(true);
    }
}
