<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExpenseService
{
    public function getAll()
    {
        return Expense::with(['tour', 'category', 'createdBy', 'updatedBy'])->latest()->get();
    }

    public function update(int $id, array $input): Expense
    {
        $expense = Expense::findOrFail($id);
        $input['updated_by'] = Auth::id();
        $expense->update($input);

        return $expense->fresh();
    }

    public function store(array $input): Expense
    {
        $input['created_by'] = Auth::id();
        return Expense::create($input);
    }

    public function show(int $id): Expense
    {
        return Expense::with(['tour', 'category'])->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $expense = $this->show($id);
        $expense->deleted_by = Auth::id();
        $expense->save();
        $expense->delete();
        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Expense::with(['tour', 'category', 'createdBy'])->latest();

        return DataTables::of($data)
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->addColumn('category_name', function ($row) {
                return $row->category->name ?? '-';
            })
            ->editColumn('amount', function ($row) {
                return '৳' . number_format($row->amount, 2);
            })
            ->editColumn('expense_date', function ($row) {
                return $row->expense_date->format('d M Y');
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser && $authUser->hasPermission('expense-update')) {
                    $editUrl = route('expense.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser && $authUser->hasPermission('expense-delete')) {
                    $deleteUrl = route('expense.destroy', $row->id);
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
            ->rawColumns(['action'])
            ->make(true);
    }
}
