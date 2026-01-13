@extends('layouts.admin.master')
@section('title', 'Send Mail')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Send Mail To All Users</h4>
                            </div>
                            <form action="{{ route('send.mail.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label" for="from_email">From</label>
                                        <input value="{{ env('MAIL_USERNAME') }}" type="text" id="from_email" name="from_email"
                                               class="form-control" placeholder="From email" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="subject">Subject</label>
                                        <input value="{{ old('subject') }}" type="text" id="subject" name="subject"
                                               class="form-control" placeholder="Subject">
                                        @error('subject')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="body" class="form-label"></label>
                                        <textarea class="form-control" name="body" id="body" cols="30" rows="4" placeholder="Message">{{ old('body') }}</textarea>
                                        @error('body')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Checkbox for specific users -->
                                    <div class="col-md-12 mt-3 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="specific_users" name="specific_users" value="1" @if (old('specific_users') == 1) checked @endif>
                                            <label class="form-label" for="specific_users">Send to Specific Users</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2" id="user_select_container" @if (old('specific_users') == 1) style="display:block;" @else style="display:none;" @endif>
                                        <label class="form-label" for="user_ids">Select Users</label>
                                        <select name="user_ids[]" id="user_ids" class="form-control multiselect select2" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-primary mt-4"><i class="fa fa-save"></i> Send</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @permission('live-comments-filter')
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header">
                                <h5 class="m-0">Filter</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="status" class="custom-select" id="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Sent</option>
                                        <option value="0">Failed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="date" class="form-control" placeholder="Start Date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endpermission
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Mail List</h4>
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
                ajax: {
                    url: "{{ route('send.mail.list') }}",
                    data: function(d) {
                        d.status = $('#status')
                            .val();
                        d.date = $('#date').val();
                    }
                },
                columns: [{
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
                        title: 'Date',
                        data: 'created_at'
                    },
                    {
                        title: 'To Mail',
                        data: 'to_email'
                    },
                    {
                        title: 'From Mail',
                        data: 'from_email'
                    },
                    {
                        title: 'Subject',
                        data: 'subject'
                    },
                    {
                        title: 'Message',
                        data: 'body'
                    },
                    {
                        title: 'Status',
                        data: 'status'
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

            $('#status, #date').change(function() {
                table.draw();
            });

            $('#specific_users').change(function() {
                $('#user_select_container').toggle(this.checked);
            });
        });
    </script>

@endpush
