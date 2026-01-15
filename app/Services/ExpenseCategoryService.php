<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryService
{
    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();
        $data = ExpenseCategory::with('createdBy')->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('created_by', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '');
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';
                
                if ($authUser && $authUser->hasPermission('expense-category-update')) {
                    $actions .= '
                        <button type="button" class="btn btn-sm btn-primary edit-btn" 
                            data-id="' . $row->id . '" 
                            data-name="' . $row->name . '">
                            <i class="fas fa-edit"></i>
                        </button>';
                }
                
                if ($authUser && $authUser->hasPermission('expense-category-delete')) {
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
            ExpenseCategory::create($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(int $id)
    {
        return ExpenseCategory::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $category = ExpenseCategory::findOrFail($id);
            $data['updated_by'] = AuthHelper::getAuthUserId();
            $category->update($data);
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
            $category = ExpenseCategory::findOrFail($id);
            
            // Check if category has expenses
            if ($category->expenses()->count() > 0) {
                throw new \Exception('Cannot delete category with existing expenses.');
            }
            
            $category->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
