@extends('layouts.admin.master')
@section('title', 'Edit Profile')

@push('css')
@endpush
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <h4 class="mb-3">Edit Profile</h4>

                            <form id="update-profile-form" action={{ route('banks.store') }} method="post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col">
                                        <div class="edit-profile-pic">
                                            @if (auth()->user()->profile_pic)
                                                <img class="profile-edit-avatar"
                                                     src="{{ asset('storage/' . auth()->user()->profile_pic) }}"
                                                     alt="img">
                                            @else
                                                <img class="profile-edit-avatar"
                                                     src="{{ asset('frontend/images/avatar.svg') }}" alt="img">
                                            @endif
                                            <div class="pp-input">
                                                <label for="profile_pic" class="profile_pic_label">Upload Photo</label>
                                                <input type="file" name="profile_pic" class="profile_pic_edit"
                                                       id="profile_pic" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="name" class="form-label">Name<span
                                                    style="vertical-align: text-top; font-family: Verdana,sans-serif;"
                                                    class="text-danger">&nbsp;*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter name" value="{{ old('name', auth()->user()->name) }}">
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="phone" class="form-label">Mobile Number<span
                                                    style="vertical-align: text-top; font-family: Verdana,sans-serif;"
                                                    class="text-danger">&nbsp;*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                   placeholder="01xxxxxxxxx" value="{{ old('phone', auth()->user()->phone) }}">
                                            @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control" id="address"
                                                   placeholder="Enter address" value="{{ old('address', auth()->user()->address) }}">
                                            @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 ml-auto">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit"
                                                    class="btn text-light radius-10 custom-bg-blue px-4 mt-4 fs-15 w-50">Update</button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <h4 class="mb-3">Change Password</h4>

                            <form id="change-password-form" action={{ route('update.password') }} method="post">
                                {{ csrf_field() }}

                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="old_password" class="form-label">Old Password</label>
                                            <input type="password" name="old_password" class="form-control"
                                                   id="old_password" placeholder="Enter old password"
                                                   value="{{ old('old_password') }}">
                                            @error('old_password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                   placeholder="Enter new password" value="{{ old('password') }}">
                                            @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-form-group">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                   id="password_confirmation" placeholder="Enter confirm password"
                                                   value="{{ old('password_confirmation') }}">
                                            @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 ml-auto">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit"
                                                    class="btn text-light radius-10 custom-bg-blue px-4 mt-4 fs-15 w-50">Change</button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- otp modal --}}
    <div class="modal fade custom-modal" id="otpModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-white">
                <div class="modal-header shadow-none">
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
                        <input id="modal-address" type="hidden" name='modalAddress' />
                        <input id="modal-name" type="hidden" name='modalName' />
                        <input id="modal-image" type="hidden" name='modalImage' />

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
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Show profile pic when user select
            $('#profile_pic').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-edit-avatar').attr('src', e.target.result);
                    $('#modal-image').val(e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });

        });
    </script>

    <script>
        var countDown;
        var isIntervalActive = false;

        //Count time
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

        //send otp function
        function sendOtp(name, phone, address, image, successCb, errorCb) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                method: 'POST',
                url: '{{ route('update-profile-otp-send') }}',
                data: {
                    _token: csrfToken,
                    name: name,
                    phone: phone,
                    address: address,
                    profile_pic: image
                },
                success: successCb,
                error: errorCb,
            });
        }

        //Resend otp
        $('#resend-otp').on('click', function(e) {
            const phoneNumber = $("#modal-phone-number").val();
            const name = $("#modal-name").val();
            const address = $("#modal-address").val();
            const image = $("#modal-image").val();
            const btn = $(this);
            const btnPrev = $(this).html();
            loadingButton($(this));

            sendOtp(
                name,
                phoneNumber,
                address,
                image,
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

        //Update profile form validation
        const updateProfileForm = $('#update-profile-form');
        updateProfileForm.on("submit", function(event) {
            event.preventDefault();
            updateProfileForm.validate({
                rules: {
                    phone: {
                        required: true,
                        remote: {
                            url: "{{ route('check-availability') }}",
                            type: "post",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                phone: function() {
                                    return $("#phone").val();
                                },
                                table: 'users',
                                ignore: '{{ auth()->user()->id ?? '' }}',
                            }
                        },
                        pattern: /(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/
                    },
                    name: {
                        required: true,
                    },
                    profile_pic: {
                        extension: "jpg,jpeg,png"
                    },
                },
                messages: {
                    profile_pic: {
                        extension: "Please select a valid file format (jpg, jpeg, png)",
                        filesize: "File size must be less than 2MB"
                    },
                    phone: {
                        remote: "Mobile number is already in use."
                    },
                }

            });


            if (updateProfileForm.valid()) {
                let button = $(updateProfileForm).find('button[type="submit"]');
                const buttonPrev = button.html();

                const phoneNumber = $("#phone").val();
                const name = $("#name").val();
                const address = $("#address").val();
                const image = $("#modal-image").val();

                $("#modal-phone-number").val(phoneNumber)
                $("#modal-address").val(address)
                $("#modal-name").val(name)
                $("#modal-show-phone").html(phoneNumber)

                if (isIntervalActive) {
                    if ('{{ auth()->user()->phone }}' != phoneNumber) {
                        $('#otpModalCenter').modal('show');
                    }
                } else {
                    loadingButton(button);
                    sendOtp(
                        name,
                        phoneNumber,
                        address,
                        image,
                        (response) => {
                            if(response.is_only_update){
                                toastr.success(response.message);
                                // setTimeout(() => {
                                //     location.href = '/dashboard/edit-profile';
                                // }, 1000);
                            }

                            if (response.success) {
                                revertLoadingButton(button, buttonPrev);
                                counting();
                                if ('{{ auth()->user()->phone }}' != phoneNumber) {
                                    $('#otpModalCenter').modal('show');
                                }
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

        //Past otp
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

        //Otp form validation
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
            const address = $("#modal-address").val();
            const image = $("#modal-image").val();


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
                    url: '/dashboard/update-profile-otp-verify',
                    data: {
                        _token: csrfToken,
                        code: enteredOTP,
                        address: address,
                        phone: phoneNumber,
                        name: name,
                        profile_pic: image
                    },
                    success: function(response) {
                        if (response.success) {
                            button.attr("disabled", false).css("cursor", "pointer");
                            button.html('Verify');
                            $('#otpModalCenter').modal('hide');
                            toastr.success(response.message);
                            // location.href = '/dashboard/edit-profile'
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

    <script>
        //Password change validation
        $(document).ready(function() {
            let changePasswordForm = $('#change-password-form');
            let validationTimer;

            let rules = {
                old_password: {
                    required: true,
                    minlength: 6
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
            };

            changePasswordForm.validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                onkeyup: function(element) {
                    let validator = this;
                    clearTimeout(validationTimer);
                    validationTimer = setTimeout(function() {
                        validator.element(element);
                    }, 1000);
                },
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: rules,
                submitHandler: function(htmlForm) {
                    let button = $(htmlForm).find('button[type="submit"]:focus');
                    button.attr("disabled", true).css("cursor", "default");
                    button.html(
                        '<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>'
                    );
                    htmlForm.submit();
                }
            });

            $.each(rules, function(key, item) {
                if (typeof item.required == "function" ? item.required() : item.required) {
                    $('label[for="' + key + '"]').first().append(
                        '<span style="vertical-align: text-top; font-family: Verdana,sans-serif;" class="text-danger">&nbsp;*</span>'
                    );
                }
            })

        });
    </script>
@endpush
