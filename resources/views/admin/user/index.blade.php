@extends('layouts.admin.master')
@section('title', 'General User List')

@push('css')

@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">General Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">General Users</li>
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
                                <h4>Members List</h4>
                                @if(auth()->check() && auth()->user()->hasPermission('general-user-create'))
                                <a href="{{ route('general.user.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-plus"></i> Add New Member
                                </a>
                                @endif
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
        var table;
        $(function() {
            table = $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: "{{ route('general.user.index') }}",
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false,
                        visible: true
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
                        title: 'Profile Pic',
                        data: 'profile_pic',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Address',
                        data: 'address'
                    },
                    {
                        title: 'Status',
                        data: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Created By',
                        data: 'created_by_name'
                    },
                    {
                        title: 'Updated By',
                        data: 'updated_by_name'
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    }
                ]
            });
        });
    </script>

@include('layouts.admin.includes.change-status', ['table' => 'users', 'column' => 'status'])

<script>
// Approve Member with Role Selection
$(document).on('click', '.approve-member-btn', function() {
    let userId = $(this).data('id');
    let userName = $(this).data('name');

    Swal.fire({
        title: 'Approve Member: ' + userName,
        html: `
            <form id="approveMemberForm">
                <div class="form-group text-left">
                    <label>Select Role(s) <span style="color: red;">*</span>:</label>
                    <select name="roles[]" class="form-control select2" multiple required>
                        @foreach(\App\Models\Role::where('name', '!=', 'admin')->get() as $role)
                        <option value="{{ $role->id }}">{{ $role->display_name ?? ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Please select at least one role for this member.</small>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Approve & Assign Role',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        preConfirm: () => {
            const selectedRoles = $('#approveMemberForm select[name="roles[]"]').val();
            if (!selectedRoles || selectedRoles.length === 0) {
                Swal.showValidationMessage('Please select at least one role');
                return false;
            }
            return selectedRoles;
        },
        didOpen: () => {
            $('.select2').select2({
                dropdownParent: $('.swal2-popup'),
                width: '100%'
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('general.user.approve', ':id') }}".replace(':id', userId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    roles: result.value
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message || 'Member approved successfully');
                        table.ajax.reload();
                    } else {
                        toastr.error(response.message || 'Failed to approve member');
                    }
                },
                error: function(xhr) {
                    let message = 'Failed to approve member';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            message = errors.join(', ');
                        }
                    }
                    toastr.error(message);
                }
            });
        }
    });
});

// Assign Role to Active Members
$(document).on('click', '.edit-role-btn', function() {
    let userId = $(this).data('id');
    let userName = $(this).data('name');
    let userRoles = $(this).data('roles');

    Swal.fire({
        title: 'Assign Role to ' + userName,
        html: `
            <form id="roleAssignForm">
                <div class="form-group text-left">
                    <label>Select Roles:</label>
                    <select name="roles[]" class="form-control select2" multiple required>
                        @foreach(\App\Models\Role::where('name', '!=', 'admin')->get() as $role)
                        <option value="{{ $role->id }}">{{ $role->display_name ?? ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Assign',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const selectedRoles = $('#roleAssignForm select[name="roles[]"]').val();
            if (!selectedRoles || selectedRoles.length === 0) {
                Swal.showValidationMessage('Please select at least one role');
                return false;
            }
            return selectedRoles;
        },
        didOpen: () => {
            $('.select2').select2({
                dropdownParent: $('.swal2-popup'),
                width: '100%'
            });
            $('.select2').val(userRoles).trigger('change');
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('general.user.assign-role', ':id') }}".replace(':id', userId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    roles: result.value
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message || 'Role assigned successfully');
                        table.ajax.reload();
                    } else {
                        toastr.error(response.message || 'Failed to assign role');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to assign role';
                    toastr.error(message);
                }
            });
        }
    });
});
</script>
@endpush
