@extends('layouts.admin.master')
@section('title', 'Edit Expense')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Expense</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('expense.index') }}">Expenses</a></li>
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
                                <h4>Edit Expense</h4>
                                <a href="{{ route('expense.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="expenseForm" action="{{ route('expense.update', $expense->id) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tour_id" class="form-label">Tour <span class="text-danger">*</span></label>
                                            <select name="tour_id" class="form-control" id="tour_id" required>
                                                <option value="">Select Tour</option>
                                                @foreach($tours as $tour)
                                                    <option value="{{ $tour->id }}" {{ old('tour_id', $expense->tour_id) == $tour->id ? 'selected' : '' }}>
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
                                            <label for="expense_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select name="expense_category_id" class="form-control" id="expense_category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('expense_category_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control" id="amount" step="0.01"
                                                   placeholder="Enter Amount" value="{{ old('amount', $expense->amount) }}" required>
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expense_date" class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="expense_date" class="form-control" id="expense_date"
                                                   value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}" required>
                                            @error('expense_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="3"
                                                      placeholder="Enter Description">{{ old('description', $expense->description) }}</textarea>
                                            @error('description')
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
            const form = $('#expenseForm');

            const rules = {
                tour_id: {
                    required: true,
                },
                expense_category_id: {
                    required: true,
                },
                amount: {
                    required: true,
                    min: 0,
                },
                expense_date: {
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
