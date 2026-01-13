@extends('layouts.admin.master')

@section('title')
    View Permission
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Permission Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">View</li>
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
                                <h4>Permission Details</h4>
                                <div>
                                    <a href="{{route('permissions.edit', [$permission->id])}}" class="btn bg-gradient-primary mr-1">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit Permission
                                    </a>
                                    <a href="{{route('permissions.index')}}" class="btn btn-primary">
                                        <i class="fa-solid fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Name</p>
                                    <div class="input-box">
                                        {{ $permission->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Display Name</p>
                                    <div class="input-box">
                                        {{ $permission->display_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Group Name</p>
                                    <div class="input-box">
                                        {{ $permission->group_name }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
