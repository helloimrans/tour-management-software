<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ValidationController extends Controller
{

    public function checkAvailability(Request $request): JsonResponse
    {
        try {
            $table = $request['table'] ?? "";
            $ignore = $request['ignore'] ?? "";
            $requestData = $request->except(['_token', 'table', 'ignore']);
            $schemaBuilder = DB::getSchemaBuilder();
            $builder = DB::table($table);
            foreach ($requestData as $key => $value) {
                if ($schemaBuilder->hasColumn($table, $key)) {
                    $builder->where($key, $value);
                }
            }

            if ($ignore) $builder->whereNot('id', $ignore);

            if ($builder->count()) {
                return response()->json(false);
            }

            return response()->json(true);
        } catch (Throwable $exception) {
            return response()->json(false);
        }
    }

    public function checkOldPassword(Request $request): JsonResponse
    {

        try {
            if (Auth::attempt(['id' => Auth::user()->id, 'password' => $request->old_password])) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        } catch (Throwable $exception) {
            return response()->json(false);
        }
    }
}
