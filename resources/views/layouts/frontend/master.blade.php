<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title') | {{ $settings->app_name ?? config('app.name', 'Tour Management') }}</title>

    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    {{-- magnific popup image --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('/frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('/css/yearpicker.css') }}">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    <!-- Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    {{-- magnific popup image --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">

    @stack('css')
</head>

<body>

    {{-- background video --}}
    <div class="background-video">
        <video autoplay muted loop>
            <source src="{{ asset('frontend/video/banner.mp4') }}" type="video/mp4">
        </video>
    </div>

    @include('layouts.frontend.includes.header')

    {{-- main content --}}
    <section class="main-content">
        @yield('content')
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/js/OverlayScrollbars.min.js"></script>
    <script>
        @if (!isset($defaultAjaxLoader))
            $(document).ajaxStart(function() {
                $("#loader-container").show();
            });

            $(document).ajaxStop(function() {
                $("#loader-container").hide();
            });
        @endif
    </script>

    <script>
        @if (\Illuminate\Support\Facades\Session::has('message'))
            let alertType = {!! json_encode(\Illuminate\Support\Facades\Session::get('alert-type', 'info')) !!};
            let alertMessage = {!! json_encode(\Illuminate\Support\Facades\Session::get('message')) !!};
            let alerter = toastr[alertType];
            alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");
        @endif
    </script>

    <script>
        window.loadingButton = (button) => {
            button.attr("disabled", true).css("cursor", "default");
            button.html('<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>');
        }

        window.revertLoadingButton = (button, prevHtml) => {
            button.removeAttr("disabled").css("cursor", "pointer");
            button.html(prevHtml);
        }
    </script>

    <script>
        // Initialize datepicker
        function initializeDatePicker() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy', // Specify the date format here
                autoclose: false,
            });
        }
        initializeDatePicker();

        $(document).ready(function() {
            $('.popup-image').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-img-mobile',
                image: {
                    verticalFit: true
                }

            });
        });
    </script>
    @stack('js')
</body>

</html>
