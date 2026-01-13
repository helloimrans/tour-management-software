@extends('layouts.admin.master')

@section('title')
    Role Permissions
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Role Permissions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
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
                            <h4>Role Permissions</h4>
                            <a href="{{route('roles.index')}}" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>
                        @if(count($permissionsGroupByTable))
                            <form class="row" method="post" action="{{route('roles.permission-sync', $role)}}">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-md-12 d-flex justify-content-between">
                                            <div class="card-title font-weight-bold">
                                                <a href="#" class="permission-select-all">{{ __('Select all') }}</a>
                                                /
                                                <a href="#" class="permission-deselect-all">{{ __('Unselect all') }}</a>
                                            </div>
                                            <div class="card-tools">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                    Sync Permission
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr/>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="card-columns permissions checkbox">
                                                <?php
                                                $role_permissions = $role->permissions->pluck('name')->toArray() ?? [];
                                                ?>
                                                @foreach($permissionsGroupByTable as $table => $permissions)

                                                    <div class="card  dashboard-custom-card" style="border: 1px solid #f0f0f0"
                                                         id="permission-for-{{empty($table) ? "general-permissions" : \Illuminate\Support\Str::slug($table)}}">
                                                        <div
                                                            class="card-header custom-bg-gradient-info d-flex justify-content-between">
                                                            <div class="font-weight-bold">
                                                                <input type="checkbox"
                                                                       id="{{empty($table) ? "general-permissions" : \Illuminate\Support\Str::slug($table)}}"
                                                                       class="permission-group">
                                                                <label
                                                                    for="{{empty($table) ? "general_permissions" : \Illuminate\Support\Str::slug($table)}}">
                                                                    @if(!empty($table))
                                                                        <strong>{{\Illuminate\Support\Str::title(str_replace('_',' ', $table))}}</strong>
                                                                    @else
                                                                        <strong>{{__("General Permissions")}}</strong>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <ul class="list-unstyled">
                                                                @php $groupByPermissions = $permissions->groupBy('sub_group'); @endphp
                                                                @foreach($groupByPermissions as $groupName => $permissions)
                                                                    @if(strlen($groupName))
                                                                        <li>
                                                                            <input type="checkbox"
                                                                                   id="permission-sub-{{\Illuminate\Support\Str::slug($groupName)}}"
                                                                                   class="permission-sub-group"
                                                                                   data-group="{{\Illuminate\Support\Str::slug($groupName)}}">
                                                                            <label class="font-weight-bold"
                                                                                   for="permission-sub-{{\Illuminate\Support\Str::slug($groupName)}}">
                                                                                {{\Illuminate\Support\Str::title($groupName)}}
                                                                            </label>
                                                                        </li>
                                                                        <ul id="permission-sub-ul-{{\Illuminate\Support\Str::slug($groupName)}}"
                                                                            style="list-style: none">
                                                                            @foreach($permissions as $perm)
                                                                                <li>
                                                                                    <input type="checkbox"
                                                                                           id="permission-{{$perm->id}}"
                                                                                           name="permissions[]"
                                                                                           class="the-permission"
                                                                                           value="{{$perm->id}}"
                                                                                           @if(in_array($perm->name, $role_permissions)) checked @endif>
                                                                                    <label class="font-weight-normal"
                                                                                           for="permission-{{$perm->id}}">{{\Illuminate\Support\Str::title(str_replace('_', ' ', $perm->name))}}</label>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        @foreach($permissions as $perm)
                                                                            <li>
                                                                                <input type="checkbox"
                                                                                       id="permission-{{$perm->id}}"
                                                                                       name="permissions[]"
                                                                                       class="the-permission"
                                                                                       value="{{$perm->id}}"
                                                                                       @if(in_array($perm->name, $role_permissions)) checked @endif>
                                                                                <label class="font-weight-normal"
                                                                                       for="permission-{{$perm->id}}">{{\Illuminate\Support\Str::title(str_replace('_', ' ', $perm->name))}}</label>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <hr/>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-sync"></i>
                                                Sync Permission
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="col-md-12 text-center">
                                <h5 class="card-subtitle text-danger">
                                    No permission key found. Please
                                    create permission key first.
                                </h5>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('document').ready(function () {


            $('.permission-group').on('change', function () {
                $('#permission-for-' + this.id).find("ul input[type='checkbox']").prop('checked', this.checked);
            });
            $('.permission-sub-group').on('change', function () {
                $(this).parent().siblings("ul").find("input[type='checkbox']").prop('checked', this.checked);
            });

            function parentChecked() {
                $('.permission-group').each(function () {
                    let allChecked = true;
                    $('#permission-for-' + this.id).find("ul input[type='checkbox']").each(function () {
                        if (!this.checked) {
                            allChecked = false;
                        }
                    });
                    $(this).prop('checked', allChecked);
                });

                $('.permission-sub-group').each(function () {
                    let allChecked = true;
                    $(this).parent().siblings("ul").find("input[type='checkbox']").each(function () {
                        if (!this.checked) {
                            allChecked = false;
                        }
                    });
                    $(this).prop('checked', allChecked);
                })

            }

            parentChecked();

            $('.the-permission').on('change', function () {
                parentChecked();
            });

            $('.permission-select-all').on('click', function () {
                $('.permission-group').prop('checked', true).trigger('change');
            });
            $('.permission-deselect-all').on('click', function () {
                $('.permission-group').prop('checked', false).trigger('change');
            });
        });
    </script>
@endpush
