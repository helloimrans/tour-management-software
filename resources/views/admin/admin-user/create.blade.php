@extends('layouts.admin.master')
@section('title', 'Create Admin User')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Admin User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Admin Users</a></li>
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
                                <h4>Create Admin User</h4>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="adminUserForm" action="{{ route('admin.user.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" id="first_name"
                                                   placeholder="Enter First Name" value="{{ old('first_name') }}">
                                            @error('first_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name"
                                                   placeholder="Enter Last Name" value="{{ old('last_name') }}">
                                            @error('last_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                   placeholder="example@gmail.com" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                   placeholder="01XXXXXXXXX" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role_id">Roles <span class="text-danger">*</span></label>
                                            <select class="form-control multiselect select2" id="role_id" name="role_id[]" multiple required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                            {{ in_array($role->id, old('role_id', [])) ? 'selected' : '' }}>
                                                        {{ $role->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                   placeholder="Enter Password" value="{{ old('password') }}">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile_pic" class="form-label">Profile Photo</label>
                                            <input type="file" name="profile_pic" class="form-control" id="profile_pic"
                                                   accept="image/*">
                                            @error('profile_pic')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="profile_preview" src="" alt="Profile Preview"
                                                     style="max-width: 200px; max-height: 200px; display: none; border-radius: 5px;">
                                            </div>
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
            const form = $('#adminUserForm');

            initImagePreview('#profile_pic', '#profile_preview');

            // Form validation rules
            const rules = {
                first_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                    pattern: /^(01[3-9]\d{8})$/,
                },
                password: {
                    required: true,
                    minlength: 5,
                },
                'role_id[]': {
                    required: true,
                    minlength: 1,
                },
                profile_pic: {
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
