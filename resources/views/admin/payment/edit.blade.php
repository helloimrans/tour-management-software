@extends('layouts.admin.master')
@section('title', 'Edit Payment')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Payment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payment.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Edit Payment</h4>
                                <a href="{{ route('payment.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="paymentForm" action="{{ route('payment.update', $payment->id) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tour_id" class="form-label">Tour <span class="text-danger">*</span></label>
                                            <select name="tour_id" class="form-control" id="tour_id" required>
                                                <option value="">Select Tour</option>
                                                @foreach($tours as $tour)
                                                    <option value="{{ $tour->id }}" {{ old('tour_id', $payment->tour_id) == $tour->id ? 'selected' : '' }}>
                                                        {{ $tour->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tour_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_id" class="form-label">Member <span class="text-danger">*</span></label>
                                            <select name="user_id" class="form-control" id="user_id" required>
                                                <option value="">Select Member</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}" {{ old('user_id', $payment->user_id) == $member->id ? 'selected' : '' }}>
                                                        {{ $member->first_name }} {{ $member->last_name }} ({{ $member->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control" id="amount" step="0.01"
                                                   placeholder="Enter Amount" value="{{ old('amount', $payment->amount) }}" required>
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                            <select name="payment_method" class="form-control" id="payment_method" required>
                                                <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="bank" {{ old('payment_method', $payment->payment_method) == 'bank' ? 'selected' : '' }}>Bank</option>
                                                <option value="bkash" {{ old('payment_method', $payment->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                                                <option value="nagad" {{ old('payment_method', $payment->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                                <option value="mobile" {{ old('payment_method', $payment->payment_method) == 'mobile' ? 'selected' : '' }}>Mobile Banking</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transaction_number" class="form-label">Transaction Number</label>
                                            <input type="text" name="transaction_number" class="form-control" id="transaction_number"
                                                   placeholder="Enter Transaction Number" value="{{ old('transaction_number', $payment->transaction_number) }}">
                                            @error('transaction_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date" class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="payment_date" class="form-control" id="payment_date"
                                                   value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}" required>
                                            @error('payment_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" id="notes" rows="3"
                                                      placeholder="Enter Notes">{{ old('notes', $payment->notes) }}</textarea>
                                            @error('notes')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-generic-validation-error-toastr />
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            const form = $('#paymentForm');

            const rules = {
                tour_id: {
                    required: true,
                },
                user_id: {
                    required: true,
                },
                amount: {
                    required: true,
                    min: 0,
                },
                payment_method: {
                    required: true,
                },
                payment_date: {
                    required: true,
                },
            };

            form.validate({
                rules: rules,
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    const submitButton = $(form).find('button[type="submit"]');
                    submitButton.prop('disabled', true)
                        .html('<i class="fa-solid fa-spinner fa-spin"></i> Updating...');
                    form.submit();
                }
            });
        });
    </script>
@endpush
