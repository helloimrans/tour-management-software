@extends('layouts.admin.master')

@section('title', 'Tour Schedule - ' . $tour->name)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tour Schedule</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tour.index') }}">Tours</a></li>
                        <li class="breadcrumb-item active">Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $tour->name }} - Schedule</h3>
                    <div class="card-tools">
                        @permission('tour-schedule-create')
                        <button type="button" class="btn btn-primary btn-sm" id="addScheduleBtn">
                            <i class="fas fa-plus"></i> Add Schedule
                        </button>
                        @endpermission
                    </div>
                </div>
                <div class="card-body">
                    <table id="scheduleTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Date</th>
                                <th width="25%">Title</th>
                                <th width="40%">Details</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="addScheduleForm">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="schedule_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea name="details" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="editScheduleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    <input type="hidden" id="editScheduleId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="schedule_date" id="editScheduleDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="editScheduleTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea name="details" id="editScheduleDetails" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // DataTable
            var table = $('#scheduleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tour.schedule.index', $tour->id) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'schedule_date', name: 'schedule_date'},
                    {data: 'title', name: 'title'},
                    {data: 'details', name: 'details'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Add Schedule
            $('#addScheduleBtn').click(function() {
                $('#addScheduleForm')[0].reset();
                $('#addScheduleModal').modal('show');
            });

            $('#addScheduleForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('tour.schedule.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addScheduleModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Failed to add schedule.');
                        }
                    }
                });
            });

            // Edit Schedule
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var date = $(this).data('date');
                var title = $(this).data('title');
                var details = $(this).data('details');

                $('#editScheduleId').val(id);
                $('#editScheduleDate').val(date);
                $('#editScheduleTitle').val(title);
                $('#editScheduleDetails').val(details);
                $('#editScheduleModal').modal('show');
            });

            $('#editScheduleForm').submit(function(e) {
                e.preventDefault();
                var id = $('#editScheduleId').val();
                $.ajax({
                    url: "/dashboard/tour-schedule/" + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editScheduleModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Failed to update schedule.');
                        }
                    }
                });
            });

            // Delete Schedule
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this schedule?')) {
                    $.ajax({
                        url: "/dashboard/tour-schedule/" + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to delete schedule.');
                        }
                    });
                }
            });
        });
    </script>
@endpush
