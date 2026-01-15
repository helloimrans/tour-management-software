<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExpenseCategoryService;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    protected ExpenseCategoryService $expenseCategoryService;

    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
    }

    public function index()
    {
        if (!auth()->user()->hasPermission('expense-category-menu')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return $this->expenseCategoryService->datatable();
        }

        return view('admin.expense-category.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('expense-category-create')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try{
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191', 'unique:expense_categories,name'],
            ]);

            $this->expenseCategoryService->store($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Category added successfully.',
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
                'message' => 'Failed to add category.',
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasPermission('expense-category-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191', 'unique:expense_categories,name,' . $id],
            ]);

            $this->expenseCategoryService->update($id, $validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
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
                'message' => 'Failed to update category.',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('expense-category-delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $this->expenseCategoryService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
