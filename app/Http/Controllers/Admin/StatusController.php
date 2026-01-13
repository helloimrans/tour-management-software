<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'table' => 'required',
            'status' => 'required|in:0,1',
            'column' => 'nullable|string',
        ]);

        try {
            $table = $request->table;
            $id = $request->id;
            $status = $request->status;
            $column = $request->column ?? 'is_active';

            DB::table($table)->where('id', $id)->update([$column => $status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
            ], 500);
        }
    }
}
