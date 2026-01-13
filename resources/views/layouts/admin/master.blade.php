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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Datatable Js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('defaults/sweetalert/sweetalertjs.js')}}"></script>

    <!-- AdminLTE App -->
    <script src="{{asset('admin/js/adminlte.min.js')}}"></script>

{{--    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-lte.js'])--}}
    @vite([ 'resources/js/app.js'])

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/css/OverlayScrollbars.min.css">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    <!-- Datatable Css -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">


    @stack('css')
    <script>
        window.select_option_placeholder = '{{ __('messages.add_button_label') }}';
    </script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.admin.includes.header')

        @include('layouts.admin.includes.sidebar')

        <div class="content-wrapper py-3">
            @yield('content')
        </div>

        @include('layouts.admin.includes.footer')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/js/OverlayScrollbars.min.js"></script>

    <script>
        @if (\Illuminate\Support\Facades\Session::has('alerts'))
            let alerts = {!! json_encode(\Illuminate\Support\Facades\Session::get('alerts')) !!};
            helpers.displayAlerts(alerts, toastr);
        @endif
        @if (\Illuminate\Support\Facades\Session::has('message'))
            let alertType = {!! json_encode(\Illuminate\Support\Facades\Session::get('alert-type', 'info')) !!};
            let alertMessage = {!! json_encode(\Illuminate\Support\Facades\Session::get('message')) !!};
            let alerter = toastr[alertType];
            alerter ? alerter(alertMessage) : toastr.error("toaster alert-type " + alertType + " is unknown");
        @endif

        window.loadingButton = (button) => {
            button.attr("disabled", true).css("cursor", "default");
            button.html('<span class="submitting"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</span>');
        }

        window.revertLoadingButton = (button, prevHtml) => {
            button.removeAttr("disabled").css("cursor", "pointer");
            button.html(prevHtml);
        }

        window.initImagePreview = function(inputSelector, previewSelector) {
            $(inputSelector).on('change', function(event) {
                if (event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewSelector).attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            });
        }

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('.select2').select2();
        })
    </script>
    @stack('js')
</body>

</html>
