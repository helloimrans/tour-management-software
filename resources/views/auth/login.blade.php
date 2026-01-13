@extends('layouts.frontend.master')
@section('title', 'Login')

@push('css')
@endpush

@section('content')
    <section class="login-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="register-box mt-4 mt-md-0">
                        <div class="bg-white-custom radius-14 padding-30">
                            <div class="text-center mb-4">
                                <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" alt="{{ $settings->app_name ?? 'Logo' }}" style="max-height: 80px; width: auto;">
                            </div>
                            <div class="login-title">
                                <p>{{__('messages.login')}}</p>
                                <h4>{{__('messages.login_title')}}</h4>
                            </div>

                            <form class="login-form" action="{{ route('admin.login') }}" method="post">
                                {{ csrf_field() }}
                                <div class="custom-form-group">
                                    <label for="email">Email or Phone Number <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        placeholder="Enter your email or phone number">
                                </div>
                                <div class="custom-form-group">
                                    <label for="password">Password <span style="color: red">*</span></label>
                                    <input type="password" class="form-control" id="password" placeholder="Enter your password"
                                        autocomplete="off" name="password">
                                </div>
                                <div class="">
                                    <a class="fs-14 text-dark d-inline-block mt-2" href="#">{{__('messages.forgot_password')}}</a>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn text-light radius-10 custom-bg-blue px-5 mt-4 fs-15 w-100">{{__('messages.login')}}
                                    </button>
                                    <p class="mt-3 text-gray fs-14">{{__('messages.dont_have_account')}} <a
                                            class="custom-color-secondary fw-500"
                                            href="#">Go to Registration</a></p>
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
    <x-generic-validation-error-toastr />
    <script>
        $(document).ready(function() {
            const loginForm = $('.login-form');

            loginForm.validate({
                rules: {
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 1,
                    }
                },
                messages: {
                    email: {
                        required: 'Please enter your phone number or email',
                    },
                    password: {
                        required: 'Please enter your password',
                    }
                },
                errorClass: 'text-danger',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    let button = $(loginForm).find('button[type="submit"]');
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
