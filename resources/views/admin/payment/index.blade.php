@extends('layouts.admin.master')
@section('title', 'Payments List')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Payments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Payments</li>
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
                                <h4>Payments List</h4>
                                @permission('payment-create')
                                <a href="{{ route('payment.create') }}" class="btn btn-primary">
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
                ajax: "{{ route('payment.index') }}",
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
                        title: 'Tour',
                        data: 'tour_name'
                    },
                    {
                        title: 'Member',
                        data: 'member_name'
                    },
                    {
                        title: 'Phone',
                        data: 'member_phone'
                    },
                    {
                        title: 'Amount',
                        data: 'amount',
                        orderable: false,
                    },
                    {
                        title: 'Payment Method',
                        data: 'payment_method',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Transaction Number',
                        data: 'transaction_number'
                    },
                    {
                        title: 'Date',
                        data: 'payment_date'
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
