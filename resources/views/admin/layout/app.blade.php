<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
     <!-- Developed By Ranglerz -->
     <link rel="stylesheet"
     href="https://www.ranglerz.com/cost-to-make-a-web-ios-or-android-app-and-how-long-does-it-take.php">
    <title>Macromed | @yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('public/admin/assets/toastr/css/toastr.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/prism/prism.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/images/Favicon-02.png') }}' />
    <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/jquery-selectric/selectric.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/admin/assets/bundles/lightgallery/dist/css/lightgallery.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/admin/toastr/toastr.css') }}">

    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

</head>
<style>
    .inactive-product {
        text-align: center;
        color: #dc3545;
        font-size: 1.2em;
        padding: 20px;
        margin-top: 10px;
        border: 1px solid #dc3545;
        border-radius: 5px;
        background-color: #f8d7da;
        margin-top: 10rem
    }
</style>

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
    <script src="{{ asset('public/admin/toastr/toastr.js') }}"></script>
    {{-- DataTbales --}}
    <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('publicadmin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('public/admin/assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/page/datatables.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/prism/prism.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/lightgallery/dist/js/lightgallery-all.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/page/light-gallery.js') }}"></script>


    @yield('js')
    <script>
        function updateOrderCounter() {
            $.ajax({
                url: "{{ route('orders.count') }}",
                type: 'GET',
                success: function(response) {
                     // Ensure response.count exists and handle counts over 99
                    let count = response.count || 0; // Default to 0 if no count is returned
                    $('#orderCounter').text(count > 99 ? '99+' : count);
                    // $('#orderCounter').text(response.count);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
        updateOrderCounter();
        setInterval(updateOrderCounter, 1000);

        function updatePaymentRequestCounter() {
            $.ajax({
                url: "{{ route('paymentRequest.count') }}",
                type: 'GET',
                success: function(response) {
                    let count = response.paymentRequest || 0;
                    // let count = 100;
                    $('.paymentRequestCounter').text(count > 99 ? '99+' : count);
                },
                error: function(xhr, status, error) {
                    console.log("error", xhr);
                }
            });
        }
        updatePaymentRequestCounter();
        setInterval(updatePaymentRequestCounter, 1000);
    </script>
    {{-- <script>
        document.getElementById('printInvoice').addEventListener('click', function() {
            // Create a new window for printing
            var printWindow = window.open('', '_blank', 'width=900,height=600');

            // Get the invoice content
            var invoiceContent = document.querySelector('.invoice').innerHTML;

            // Write the invoice content to the new window
            printWindow.document.write(`
            <html>
                <head>
                    <title>Invoice Print</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                    <style>
                        body {
                            margin: 20px;
                            font-family: Arial, sans-serif;
                        }
                        /* Custom styles for print */
                        @media print {
                            button {
                                display: none; /* Hide buttons when printing */
                            }
                        }
                    </style>
                </head>
                <body onload="window.print();window.close();">
                    <div class="invoice">${invoiceContent}</div>
                </body>
            </html>
        `);

            // Close the document for rendering
            printWindow.document.close();
        });
    </script> --}}



    <script>
        document.getElementById('printInvoice').addEventListener('click', function() {
            // Get the invoice content
            var invoiceContent = document.querySelector('.invoice').innerHTML;

            // Open a new window for printing
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Invoice Print</title>
                        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                        <style>
                            body {
                                margin: 20px;
                                font-family: Arial, sans-serif;
                            }
                            @media print {
                                button {
                                    display: none;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="invoice">${invoiceContent}</div>
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(function() {
                                    window.close();
                                }, 100); // Delay to allow print dialog to load
                            }
                        <\/script>
                    </body>
                </html>
            `);

            printWindow.document.close(); // Close the document stream
        });

    </script>

    <script>
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": 1000,
            "extendedTimeOut": 1000
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
