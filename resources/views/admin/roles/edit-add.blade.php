@php
    $edit = !empty($role->id);
@endphp
@extends('layouts.admin.master')

@section('title')
    {{ $edit?'Edit Role':'Create Role' }}
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $edit?'Edit Role':'Create Role' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">{{ $edit?'Edit':'Create' }}</li>
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
                                <h4>{{ $edit?'Edit Role':'Create Role' }}</h4>
                                <a href="{{route('roles.index')}}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form
                                action="{{$edit ? route('roles.update', $role->id) : route('roles.store')}}"
                                method="POST" class="edit-add-form">
                                @csrf
                                @if($edit)
                                    @method('put')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   value="{{$edit ? $role->name : old('name')}}"
                                                   placeholder="Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="display_name" id="display_name"
                                                   value="{{$edit ? $role->display_name : old('display_name')}}"
                                                   placeholder="Display Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="4"
                                                      placeholder="Description">{{$edit ? $role->description : old('description')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> {{ $edit?'Update Role':'Create Role' }}
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

@push('css')
    <style>
        .custom-radio-is-deletable{
            margin-right: 25px;
        }
    </style>
@endpush
@push('js')
    <x-generic-validation-error-toastr/>
    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                display_name: {
                    required: true,
                },
                name: {
                    required: true
                },
                // is_deletable: {
                //     required: true
                // }
            },
            messages:{
                name: {
                    pattern: "This field is required in English.",
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
