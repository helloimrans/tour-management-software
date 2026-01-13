@php
    $edit = !empty($permission->id);
@endphp
@extends('layouts.admin.master')

@section('title')
    {{ $edit ? 'Edit Permission' : 'Create Permission' }}
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $edit ? 'Edit Permission' : 'Create Permission' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">{{ $edit ? 'Edit' : 'Create' }}</li>
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
                                <h4>{{ $edit ? 'Edit Permission' : 'Create Permission' }}</h4>
                                <a href="{{ route('permissions.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form
                                action="{{ $edit ? route('permissions.update', $permission->id) : route('permissions.store') }}"
                                method="POST" class="edit-add-form">
                                @csrf
                                @if ($edit)
                                    @method('put')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   value="{{ $edit ? $permission->name : old('name') }}"
                                                   placeholder="Enter unique permission name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="display_name" id="display_name"
                                                   value="{{ $edit ? $permission->display_name : old('display_name') }}"
                                                   placeholder="Display Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="group_name" id="group_name"
                                                   value="{{ $edit ? $permission->group_name : old('group_name') }}"
                                                   placeholder="Group name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> {{ $edit ? 'Update Permission' : 'Create Permission' }}
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
    <x-generic-validation-error-toastr />
    <script>
        const EDIT = !!'{{ $edit }}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                name: {
                    required: true
                },
                display_name: {
                    required: true
                }
            },
            submitHandler: function(htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
