@extends('layouts.frontend.master')
@section('title', 'Member Registration')

@push('css')
@endpush

@section('content')
    <section class="login-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="register-box mt-4 mt-md-0">
                        <div class="bg-white-custom radius-14 padding-30">
                            <div class="text-center mb-4">
                                <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" alt="{{ $settings->app_name ?? 'Logo' }}" style="max-height: 80px; width: auto;">
                            </div>
                            <div class="login-title">
                                <p>Registration</p>
                                <h4>Create Your Account</h4>
                            </div>

                            <form id="memberRegisterForm" class="login-form" action="{{ route('member.register') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="first_name">First Name <span style="color: red">*</span></label>
                                            <input type="text" name="first_name" class="form-control" id="first_name"
                                                   placeholder="Enter First Name" value="{{ old('first_name') }}">
                                            @error('first_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name"
                                                   placeholder="Enter Last Name" value="{{ old('last_name') }}">
                                            @error('last_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                   placeholder="example@gmail.com" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="phone">Phone Number <span style="color: red">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                   placeholder="01XXXXXXXXX" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="password">Password <span style="color: red">*</span></label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                   placeholder="Enter Password">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="password_confirmation">Confirm Password <span style="color: red">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                                                   placeholder="Confirm Password">
                                        </div>
                                    </div>
                                </div>

                                <div class="custom-form-group">
                                    <label for="address">Address</label>
                                    <textarea name="address" class="form-control" id="address" rows="3"
                                              placeholder="Enter Address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="custom-form-group">
                                    <label for="profile_pic">Profile Photo</label>
                                    <input type="file" name="profile_pic" class="form-control" id="profile_pic"
                                           accept="image/*">
                                    @error('profile_pic')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="profile_preview" src="" alt="Profile Preview"
                                             style="max-width: 200px; max-height: 200px; display: none; border-radius: 5px;">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn text-light radius-10 custom-bg-blue px-5 mt-4 fs-15 w-100">
                                        <i class="fa-solid fa-user-plus"></i> Register
                                    </button>
                                    <p class="mt-3 text-gray fs-14">Already have an account? <a
                                            class="custom-color-secondary fw-500"
                                            href="{{ route('login') }}">Login Here</a></p>
                                    <p class="mt-2">
                                        <a href="{{ route('landing') }}" class="fs-14 text-dark">
                                            <i class="fa-solid fa-arrow-left"></i> Back to Home
                                        </a>
                                    </p>

                                    <div class="copyright-login">
                                        <p>Copyright {{ date('Y') }} All rights Reserved</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    @if(file_exists(resource_path('views/components/generic-validation-error-toastr.blade.php')))
        <x-generic-validation-error-toastr />
    @endif
    <script>
        $(document).ready(function() {
            // Initialize image preview
            if (typeof initImagePreview === 'function') {
                initImagePreview('#profile_pic', '#profile_preview');
            } else {
                $('#profile_pic').on('change', function(event) {
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#profile_preview').attr('src', e.target.result).show();
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    }
                });
            }

            const registerForm = $('#memberRegisterForm');

            registerForm.validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 191,
                    },
                    last_name: {
                        maxlength: 191,
                    },
                    email: {
                        email: true,
                        maxlength: 191,
                    },
                    phone: {
                        required: true,
                        pattern: /^(01[3-9]\d{8})$/,
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#password',
                    },
                    address: {
                        maxlength: 500,
                    },
                    profile_pic: {
                        accept: 'image/*',
                    }
                },
                messages: {
                    first_name: {
                        required: 'Please enter your first name',
                        maxlength: 'First name cannot exceed 191 characters',
                    },
                    last_name: {
                        maxlength: 'Last name cannot exceed 191 characters',
                    },
                    email: {
                        email: 'Please enter a valid email address',
                        maxlength: 'Email cannot exceed 191 characters',
                    },
                    phone: {
                        required: 'Please enter your phone number',
                        pattern: 'Please enter a valid phone number (01XXXXXXXXX)',
                    },
                    password: {
                        required: 'Please enter a password',
                        minlength: 'Password must be at least 5 characters long',
                    },
                    password_confirmation: {
                        required: 'Please confirm your password',
                        equalTo: 'Passwords do not match',
                    },
                    address: {
                        maxlength: 'Address cannot exceed 500 characters',
                    },
                    profile_pic: {
                        accept: 'Please select a valid image file',
                    }
                },
                errorClass: 'text-danger',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    if (element.closest('.row').length) {
                        error.insertAfter(element.closest('.custom-form-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    let button = $(registerForm).find('button[type="submit"]');
                    button.attr("disabled", true).css("cursor", "default");
                    button.html('<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>');
                    if ($('.overlay').length) {
                        $('.overlay').show();
                    }
                    form.submit();
                }
            });
        });
    </script>
@endpush
