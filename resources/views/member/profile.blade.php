@extends('layouts.admin.master')
@section('title', 'My Profile')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
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
                                <h4>Update Profile</h4>
                                <a href="{{ route('member.dashboard') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="memberProfileForm" action="{{ route('member.profile.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" id="first_name"
                                                   placeholder="Enter First Name" value="{{ old('first_name', $user->first_name) }}">
                                            @error('first_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name"
                                                   placeholder="Enter Last Name" value="{{ old('last_name', $user->last_name) }}">
                                            @error('last_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                   placeholder="example@gmail.com" value="{{ old('email', $user->email) }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                   placeholder="01XXXXXXXXX" value="{{ old('phone', $user->phone) }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea name="address" class="form-control" id="address" rows="3"
                                                      placeholder="Enter Address">{{ old('address', $user->address) }}</textarea>
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile_pic" class="form-label">Profile Photo</label>
                                            <input type="file" name="profile_pic" class="form-control" id="profile_pic"
                                                   accept="image/*">
                                            @error('profile_pic')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="profile_preview" src="{{ $user->profile_pic_url }}" alt="Profile Preview"
                                                     style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initImagePreview('#profile_pic', '#profile_preview');
        });
    </script>
@endpush

