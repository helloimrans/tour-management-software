<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentService
{
    public function getAll()
    {
        return Payment::with(['tour', 'user', 'createdBy', 'updatedBy'])->latest()->get();
    }

    public function update(int $id, array $input): Payment
    {
        $payment = Payment::findOrFail($id);
        $input['updated_by'] = Auth::id();
        $payment->update($input);

        return $payment->fresh();
    }

    public function store(array $input): Payment
    {
        $input['created_by'] = Auth::id();
        return Payment::create($input);
    }

    public function show(int $id): Payment
    {
        return Payment::with(['tour', 'user'])->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $payment = $this->show($id);
        $payment->deleted_by = Auth::id();
        $payment->save();
        $payment->delete();
        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Payment::with(['tour', 'user', 'createdBy'])->latest();

        return DataTables::of($data)
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->addColumn('member_name', function ($row) {
                return ($row->user->first_name ?? '') . ' ' . ($row->user->last_name ?? '');
            })
            ->addColumn('member_phone', function ($row) {
                return $row->user->phone ?? '-';
            })
            ->editColumn('amount', function ($row) {
                return '৳' . number_format($row->amount, 2);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->format('d M Y');
            })
            ->editColumn('payment_method', function ($row) {
                $badges = [
                    'cash' => 'badge-success',
                    'bank' => 'badge-info',
                    'bkash' => 'badge-warning',
                    'nagad' => 'badge-primary',
                    'mobile' => 'badge-secondary',
                ];
                $class = $badges[$row->payment_method] ?? 'badge-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($row->payment_method) . '</span>';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser && $authUser->hasPermission('payment-update')) {
                    $editUrl = route('payment.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser && $authUser->hasPermission('payment-delete')) {
                    $deleteUrl = route('payment.destroy', $row->id);
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
            ->rawColumns(['action', 'payment_method'])
            ->make(true);
    }
}
