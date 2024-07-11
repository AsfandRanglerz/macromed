<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Admin Dashboard</title>
    <!-- General CSS Files -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/images/logo-macromed.png') }}' />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/toastr/toastr.css') }}">
    {{-- <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/css/responsive.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}"> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/select2/dist/css/select2.min.css') }}">


</head>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('admin.common.header')
            @include('admin.common.side_menu')
            @yield('content')
            @include('admin.common.footer')
        </div>
    </div>
    {{-- <script src="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/js/dataTables.responsive.js') }}">
    </script>
     <script src="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
    </script>
      <script src="{{ asset('public/admin/assets/bundles/datatables/datatables-responsive/js/responsive.bootstrap4.js') }}">
    </script>
      <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}">
    </script> --}}
    <!-- General JS Scripts -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('public/admin/assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>

    <script src="{{ asset('public/admin/assets/js/datatables.js') }}"></script>

    <script src="{{ asset('public/admin/toastr/toastr.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>


    @yield('js')
    <script>
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000"
        };

        @if (session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
