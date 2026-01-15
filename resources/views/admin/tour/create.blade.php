@extends('layouts.admin.master')
@section('title', 'Create Tour')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tour.index') }}">Tours</a></li>
                        <li class="breadcrumb-item active">Create</li>
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
                                <h4>Create Tour</h4>
                                <a href="{{ route('tour.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="tourForm" action="{{ route('tour.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Tour Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter Tour Name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                                            <input type="text" name="destination" class="form-control" id="destination"
                                                   placeholder="Enter Destination" value="{{ old('destination') }}">
                                            @error('destination')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control" id="start_date"
                                                   value="{{ old('start_date') }}">
                                            @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                            <input type="date" name="end_date" class="form-control" id="end_date"
                                                   value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_cost" class="form-label">Total Cost (৳)</label>
                                            <input type="number" name="total_cost" class="form-control" id="total_cost"
                                                   placeholder="Enter Total Cost" value="{{ old('total_cost', 0) }}" step="0.01" min="0">
                                            @error('total_cost')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="per_member_cost" class="form-label">Cost Per Member (৳)</label>
                                            <input type="number" name="per_member_cost" class="form-control" id="per_member_cost"
                                                   placeholder="Enter Cost Per Member" value="{{ old('per_member_cost', 0) }}" step="0.01" min="0">
                                            @error('per_member_cost')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_members" class="form-label">Max Members <span class="text-danger">*</span></label>
                                            <input type="number" name="max_members" class="form-control" id="max_members"
                                                   placeholder="Enter Max Members" value="{{ old('max_members', 0) }}" min="1">
                                            @error('max_members')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" id="status">
                                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Tour Image</label>
                                            <input type="file" name="image" class="form-control" id="image"
                                                   accept="image/*">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="image_preview" src="" alt="Image Preview"
                                                     style="max-width: 200px; max-height: 200px; display: none; border-radius: 5px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="4"
                                                      placeholder="Enter Description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Submit
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            const form = $('#tourForm');

            initImagePreview('#image', '#image_preview');

            const rules = {
                name: {
                    required: true,
                },
                destination: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                max_members: {
                    required: true,
                    min: 1,
                },
                status: {
                    required: true,
                },
                image: {
                    accept: "image/*"
                }
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
                        .html('<i class="fa-solid fa-spinner fa-spin"></i> Submitting...');
                    form.submit();
                }
            });
        });
    </script>
@endpush
