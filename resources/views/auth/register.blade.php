@extends('layouts.frontend.master')
@section('title', 'Register')

@push('css')
@endpush

@push('css')
@endpush
@section('content')
    <section class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-md-8 align-self-center">
                    @include('auth.auth-slider')
                </div>
                <div class="col-md-4">
                    <div class="register-box">
                        <div class="bg-white-custom padding-30 radius-14">
                            <div class="login-title">
                                <p>{{ __('messages.signup') }}</p>
                                <h4>{{ __('messages.signup_title') }}</h4>
                            </div>

                            <form class="merchant-registration-form">
                                <div class="custom-form-group">
                                    <label for="name">Name <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        aria-describedby="nameHelp" placeholder="Enter name">
                                </div>

                                <div class="custom-form-group">
                                    <label for="phone">Mobile Number <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        aria-describedby="phoneHelp" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="custom-form-group">
                                    <label for="password">New Password <span style="color: red">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="New password">
                                </div>

                                <div class="custom-form-group">
                                    <label for="confirm_password">Confirm Password <span style="color: red">*</span></label>
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Confirm password">
                                </div>

                                <div class="text-center">
                                    {{-- <button type="submit"
                                        class="btn w-100 radius-10 text-light custom-bg-blue px-5 mt-4 fs-15" style=""
                                        data-target="#otpModalCenter">Signup
                                    </button> --}}
                                    <p class="mt-3 text-gray fs-14">Already have account? <a
                                            class="custom-color-secondary fw-500"
                                            href="{{ route('show.login.form') }}">Login</a></p>

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

    <div class="modal fade custom-modal" id="otpModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 p-md-5 pb-3">
                    <form action="" class="otp-form">
                        @csrf
                        <div class="text-center">
                            <h6 class="fs-18 mb-4">Enter OTP</h6>
                            <p class="text-dark fs-14 fw-500"><span>A code has been sent to your
                                    mobile number which you enter - <span id="modal-show-phone"></span></span> <small
                                    class="verification-phone-number"></small>
                            </p>
                        </div>
                        <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                            <input class="m-2 text-center form-control rounded otp-field" type="text" id="first"
                                maxlength="1" />
                            <input class="m-2 text-center form-control rounded otp-field" type="text" id="second"
                                maxlength="1" />
                            <input class="m-2 text-center form-control rounded otp-field" type="text" id="third"
                                maxlength="1" />
                            <input class="m-2 text-center form-control rounded otp-field" type="text" id="fourth"
                                maxlength="1" />
                        </div>
                        <input id="entered-code" type="hidden" name="entered_code" class="entered-code" />
                        <input id="modal-phone-number" type="hidden" name='modalPhoneNumber' />
                        <input id="modal-password" type="hidden" name='modalPassword' />
                        <input id="modal-name" type="hidden" name='modalName' />
                        <div class="text-center pt-3">
                            <span id="expire-text" class="text-dark">OTP expires in </span>
                            <span id="timer">
                                <span class="custom-color-blue fw-500" id="countdown-minutes">02</span>:<span
                                    class="custom-color-blue fw-500" id="countdown-seconds">00</span> <span
                                    class="custom-color-blue fw-500">min</span>
                            </span>
                        </div>
                        <div class="verify-button text-center">
                            <button type="button" class="btn custom-color-blue fw-600" id="resend-otp">Resend</button>
                        </div>
                        <div class="verify-button text-center mt-3">
                            <button type="submit" class="btn custom-bg-blue text-white px-5"
                                id="verify-otp">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr />

    <script>
        var countDown;
        var isIntervalActive = false;

        function counting() {
            var countdownSeconds = 120;
            $('#expire-text').html('OTP expires in');
            $('#timer').show();
            $('#resend-otp').hide();

            isIntervalActive = true;
            countDown = setInterval(() => {
                countdownSeconds--;
                if (countdownSeconds === 0) {
                    $('#expire-text').html('OTP Expired. Please Resend');
                    countdownSeconds = 0;
                    $('#timer').hide();
                    $('#resend-otp').show();
                    isIntervalActive = false;
                    clearInterval(countDown);
                }
                const minutes = Math.floor(countdownSeconds / 60);
                const seconds = countdownSeconds % 60;

                const minutesDisplay = String(minutes).padStart(2, '0');
                const secondsDisplay = String(seconds).padStart(2, '0');

                $('#countdown-minutes').html(minutesDisplay);
                $('#countdown-seconds').html(secondsDisplay);
            }, 1000);
        }



        function sendOtp(name, phone, successCb, errorCb) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                method: 'POST',
                url: '{{ route('merchant-otp-send') }}',
                data: {
                    _token: csrfToken,
                    name: name,
                    phone: phone
                },
                success: successCb,
                error: errorCb,
            });
        }

        $('#resend-otp').on('click', function(e) {
            const phoneNumber = $("#modal-phone-number").val();
            const name = $("#modal-name").val();
            const btn = $(this);
            const btnPrev = $(this).html();
            loadingButton($(this));

            sendOtp(
                name,
                phoneNumber,
                (response) => {
                    if (response.success) {
                        counting();
                        revertLoadingButton(btn, btnPrev);
                    } else {
                        revertLoadingButton(btn, btnPrev);
                        toastr.error(response.message);
                    }
                },
                (response) => {
                    console.log(response.error);
                    revertLoadingButton(btn, btnPrev);
                }
            )
        })

        $('#phone').on('change', () => {
            isIntervalActive = false;
            clearInterval(countDown)
        });

        const loginForm = $('.merchant-registration-form');

        loginForm.on("submit", function(event) {
            event.preventDefault();
            loginForm.validate({
                rules: {
                    phone: {
                        required: true,
                        pattern: /(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/
                    },
                    name: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    confirm_password: {
                        equalTo: "Passwords do not match"
                    }
                }

            });


            if (loginForm.valid()) {
                let button = $(loginForm).find('button[type="submit"]');
                const buttonPrev = button.html();

                const phoneNumber = $("#phone").val();
                const name = $("#name").val();
                const password = $("#password").val();

                $("#modal-phone-number").val(phoneNumber)
                $("#modal-password").val(password)
                $("#modal-name").val(name)
                $("#modal-show-phone").html(phoneNumber)

                if (isIntervalActive) {
                    $('#otpModalCenter').modal('show');
                } else {
                    loadingButton(button);
                    sendOtp(
                        name,
                        phoneNumber,
                        (response) => {
                            if (response.success) {
                                revertLoadingButton(button, buttonPrev);
                                counting();
                                $('#otpModalCenter').modal('show');
                            } else {
                                revertLoadingButton(button, buttonPrev);
                                toastr.error(response.message);
                            }
                        },
                        (response) => {
                            console.log(response.error);
                            revertLoadingButton(button, buttonPrev);
                        }
                    )
                }
            }

        });

        $(document).ready(function() {
            $(".otp-form *:input[type!=hidden]:first").focus();
            let otpFields = $(".otp-form .otp-field"),
                otp_value_field = $(".otp-form .otp-value");
            otpFields
                .on("input", function(e) {
                    $(this).val(
                        $(this)
                        .val()
                        .replace(/[^0-9]/g, "")
                    );
                    let optValue = "";
                    otpFields.each(function() {
                        let fieldValue = $(this).val();
                        if (fieldValue != "") optValue += fieldValue;
                    });
                    otp_value_field.val(optValue);
                })
                .on("keyup", function(e) {
                    let key = e.keyCode || e.charCode;
                    if (key == 8 || key == 46 || key == 37 || key == 40) {
                        $(this).prev().focus();
                    } else if (key == 38 || key == 39 || $(this).val() != "") {
                        $(this).next().focus();
                    }
                })
                .on("paste", function(e) {
                    let paste_data = e.originalEvent.clipboardData.getData("text");
                    let paste_data_splitted = paste_data.split("");
                    $.each(paste_data_splitted, function(index, value) {
                        otpFields.eq(index).val(value);
                    });
                });


            $('#close-modal').click(function() {
                let button = $('.otp-form').find('button[type="submit"]');
                button.attr("disabled", false).css("cursor", "pointer");
                button.html('Verify');
                $('#otpModalCenter').modal('hide');
            });

            $('#otpModalCenter').on('show.bs.modal', function() {
                $('.otp-form .otp-field').val('');
            });
        });

        const otpForm = $('.otp-form');
        otpForm.on("submit", function(event) {
            event.preventDefault();

            const first = $('#first').val();
            const second = $('#second').val();
            const third = $('#third').val();
            const fourth = $('#fourth').val();

            const enteredOTP = first + second + third + fourth;
            const phoneNumber = $("#modal-phone-number").val();
            const name = $("#modal-name").val();
            const password = $("#modal-password").val();


            $('#entered-code').val(enteredOTP);

            otpForm.validate({
                rules: {
                    entered_code: {
                        required: true,
                        minlength: 4,
                        maxlength: 4
                    }
                },
                messages: {
                    entered_code: {
                        required: "Please enter the OTP code.",
                        minlength: "Please enter a 4-digit OTP code.",
                        maxlength: "Please enter a 4-digit OTP code."
                    }
                },

            });
            if (otpForm.valid()) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                let button = $(otpForm).find('button[type="submit"]');
                button.attr("disabled", true).css("cursor", "default");
                button.html('<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>');
                $.ajax({
                    method: 'POST',
                    url: '/merchant-otp-verify',
                    data: {
                        _token: csrfToken,
                        code: enteredOTP,
                        password: password,
                        phone: phoneNumber,
                        name: name
                    },
                    success: function(response) {
                        if (response.success) {
                            button.attr("disabled", false).css("cursor", "pointer");
                            button.html('Verify');
                            $('#otpModalCenter').modal('hide');
                            toastr.success(response.message);
                            location.href = '/sme-form'
                        } else {
                            button.attr("disabled", false).css("cursor", "pointer");
                            button.html('Verify');
                            toastr.error(response.message);
                        }
                    },
                    error: function(res) {
                        toastr.error(res.responseJSON.message);
                        button.attr("disabled", false).css("cursor", "pointer");
                        button.html('Verify');
                    }
                });
            }
        });
    </script>
@endpush
