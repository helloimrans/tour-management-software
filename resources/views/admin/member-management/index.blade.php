@extends('layouts.admin.master')
@section('title', 'Member Management')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Member Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Member Management</li>
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
                                <h4>Tour Participants</h4>
                                @permission('member-management-create')
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addMemberModal">
                                    <i class="fa-solid fa-circle-plus"></i> Add Member to Tour
                                </button>
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

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('member-management.add-to-tour') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Member to Tour</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tour_id">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="tour_id" class="form-control" required>
                                <option value="">Select Tour</option>
                                @foreach($tours as $tour)
                                    <option value="{{ $tour->id }}">{{ $tour->name }} - {{ $tour->destination }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Member <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }} ({{ $member->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="room_no">Room No</label>
                            <input type="text" name="room_no" id="room_no" class="form-control" placeholder="Enter Room No">
                        </div>
                        <div class="form-group">
                            <label for="seat_no">Seat No</label>
                            <input type="text" name="seat_no" id="seat_no" class="form-control" placeholder="Enter Seat No">
                        </div>
                        <div class="form-group">
                            <label for="join_status">Status <span class="text-danger">*</span></label>
                            <select name="join_status" id="join_status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="approved" selected>Approved</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editMemberForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Member Details</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_room_no">Room No</label>
                            <input type="text" name="room_no" id="edit_room_no" class="form-control" placeholder="Enter Room No">
                        </div>
                        <div class="form-group">
                            <label for="edit_seat_no">Seat No</label>
                            <input type="text" name="seat_no" id="edit_seat_no" class="form-control" placeholder="Enter Seat No">
                        </div>
                        <div class="form-group">
                            <label for="edit_join_status">Status <span class="text-danger">*</span></label>
                            <select name="join_status" id="edit_join_status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
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

@push('js')
    <script>
        $(function() {
            $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('member-management.index') }}",
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
                        title: 'Member Name',
                        data: 'member_name'
                    },
                    {
                        title: 'Phone',
                        data: 'member_phone'
                    },
                    {
                        title: 'Tour Name',
                        data: 'tour_name'
                    },
                    {
                        title: 'Destination',
                        data: 'tour_destination'
                    },
                    {
                        title: 'Room & Seat',
                        data: 'room_seat',
                        orderable: false,
                    },
                    {
                        title: 'Status',
                        data: 'join_status',
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

            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const roomNo = $(this).data('room-no');
                const seatNo = $(this).data('seat-no');
                const joinStatus = $(this).data('join-status');

                $('#edit_room_no').val(roomNo);
                $('#edit_seat_no').val(seatNo);
                $('#edit_join_status').val(joinStatus);

                $('#editMemberForm').attr('action', '{{ route('member-management.update', '') }}/' + id);
                $('#editMemberModal').modal('show');
            });
        });
    </script>
@endpush
