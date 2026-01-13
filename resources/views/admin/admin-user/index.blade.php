@extends('layouts.admin.master')
@section('title', 'Admin Users List')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Admin Users</li>
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
                                <h4>Admin Users List</h4>
                                @permission('admin-user-create')
                                <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-plus"></i> Add New
                                </a>
                                @endpermission
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable custom-table dt-responsive nowrap">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('admin.user.index') }}",
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false,
                    },
                    {
                        title: 'First Name',
                        data: 'first_name'
                    },
                    {
                        title: 'Last Name',
                        data: 'last_name'
                    },
                    {
                        title: 'Email',
                        data: 'email'
                    },
                    {
                        title: 'Phone',
                        data: 'phone'
                    },
                    {
                        title: 'Roles',
                        data: 'roles'
                    },
                    {
                        title: 'Profile Pic',
                        data: 'profile_pic',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Status',
                        data: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Created By',
                        data: 'created_by_name',
                        orderable: false,
                    },
                    {
                        title: 'Updated By',
                        data: 'updated_by_name',
                        orderable: false,
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });
    </script>

    @include('layouts.admin.includes.change-status', ['table' => 'users', 'column' => 'status'])
@endpush
