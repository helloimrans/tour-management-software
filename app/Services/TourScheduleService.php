<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TourScheduleService
{
    public function datatable($tourId)
    {
        $authUser = AuthHelper::getAuthUser();
        $data = TourSchedule::where('tour_id', $tourId)
            ->with('createdBy')
            ->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('schedule_date', function ($row) {
                return $row->schedule_date->format('d M Y');
            })
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('details', function ($row) {
                return $row->details ? \Str::limit($row->details, 50) : '--';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';
                
                if ($authUser && $authUser->hasPermission('tour-schedule-update')) {
                    $actions .= '
                        <button type="button" class="btn btn-sm btn-primary edit-btn" 
                            data-id="' . $row->id . '" 
                            data-tour-id="' . $row->tour_id . '"
                            data-date="' . $row->schedule_date->format('Y-m-d') . '" 
                            data-title="' . $row->title . '" 
                            data-details="' . ($row->details ?? '') . '">
                            <i class="fas fa-edit"></i>
                        </button>';
                }
                
                if ($authUser && $authUser->hasPermission('tour-schedule-delete')) {
                    $actions .= '
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                            <i class="fas fa-trash"></i>
                        </button>';
                }
                
                return $actions ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = AuthHelper::getAuthUserId();
            TourSchedule::create($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(int $id)
    {
        return TourSchedule::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $tourSchedule = TourSchedule::findOrFail($id);
            $data['updated_by'] = AuthHelper::getAuthUserId();
            $tourSchedule->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $tourSchedule = TourSchedule::findOrFail($id);
            $tourSchedule->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
