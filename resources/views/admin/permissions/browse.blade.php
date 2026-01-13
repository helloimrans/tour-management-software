@extends('layouts.admin.master')
@section('title')
    Permissions List
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Permissions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Permissions</li>
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
                                <h4>Permissions List</h4>
                                @permission('permissions-create')
                                <a href="{{ route('permissions.create') }}" class="btn btn-primary">
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
    @include('utils.delete-confirm-modal')
@endsection

@push('js')
    <script>
        var table;
        $(function() {
            table = $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('permissions.datatable') }}",
                columns: [{
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        title: "Name",
                        data: "name"
                    },
                    {
                        title: "Display Name",
                        data: "display_name"
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });
        });
    </script>
@endpush
