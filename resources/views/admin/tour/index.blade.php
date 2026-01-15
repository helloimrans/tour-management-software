@extends('layouts.admin.master')
@section('title', 'Tours List')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tours</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tours</li>
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
                                <h4>Tours List</h4>
                                @permission('tour-create')
                                <a href="{{ route('tour.create') }}" class="btn btn-primary">
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
                ajax: "{{ route('tour.index') }}",
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
                        title: 'Image',
                        data: 'image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Tour Name',
                        data: 'name'
                    },
                    {
                        title: 'Destination',
                        data: 'destination'
                    },
                    {
                        title: 'Dates',
                        data: 'dates',
                        orderable: false,
                    },
                    {
                        title: 'Cost/Member',
                        data: 'cost',
                        orderable: false,
                    },
                    {
                        title: 'Members',
                        data: 'members',
                        orderable: false,
                    },
                    {
                        title: 'Status',
                        data: 'status',
                        orderable: false,
                        searchable: false,
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

@endpush

