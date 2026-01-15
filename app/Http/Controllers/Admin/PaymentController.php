<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\TourService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected TourService $tourService;

    public function __construct(PaymentService $paymentService, TourService $tourService)
    {
        $this->paymentService = $paymentService;
        $this->tourService = $tourService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->paymentService->datatable();
        }

        return view('admin.payment.index');
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('payment-create')) {
            abort(403, 'Unauthorized action.');
        }
        $data['tours'] = $this->tourService->getAll();
        $data['members'] = User::where('user_type', User::NORMAL_USER_CODE)->get();

        return view('admin.payment.create', $data);
    }

    public function edit(string $id)
    {
        if (!auth()->user()->hasPermission('payment-update')) {
            abort(403, 'Unauthorized action.');
        }
        $data['payment'] = $this->paymentService->show($id);
        $data['tours'] = $this->tourService->getAll();
        $data['members'] = User::where('user_type', User::NORMAL_USER_CODE)->get();

        return view('admin.payment.edit', $data);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('payment-create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'integer', 'exists:tours,id'],
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'payment_method' => ['required', 'string', 'max:191'],
                'transaction_number' => ['nullable', 'string', 'max:191'],
                'payment_date' => ['required', 'date'],
                'notes' => ['nullable', 'string'],
            ]);

            $this->paymentService->store($validatedData);

            return redirect()->route('payment.index')->with([
                'message' => 'Payment added successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to add payment. Please try again.'])->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasPermission('payment-update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'integer', 'exists:tours,id'],
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'payment_method' => ['required', 'string', 'max:191'],
                'transaction_number' => ['nullable', 'string', 'max:191'],
                'payment_date' => ['required', 'date'],
                'notes' => ['nullable', 'string'],
            ]);

            $this->paymentService->update($id, $validatedData);

            return redirect()->route('payment.index')->with([
                'message' => 'Payment updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update payment. Please try again.'])->withInput();
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('payment-delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $this->paymentService->delete($id);

            return redirect()->route('payment.index')->with([
                'message' => 'Payment deleted successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete payment. Please try again.']);
        }
    }
}
