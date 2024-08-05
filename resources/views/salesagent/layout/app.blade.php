<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Sales Agent Dashboard</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('public/admin/assets/toastr/css/toastr.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/prism/prism.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/Fav Icon 2.png') }}' />
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
    .no-notifications {
        text-align: center;
        /* Center-align the text */
        color: #dc3545;
        /* Bootstrap's danger color for red */
        font-size: 1.2em;
        /* Slightly larger font size */
        padding: 10px;
        /* Add some padding */
        margin-top: 10px;
        /* Add margin on top */
        /* border: 1px solid #dc3545; */
        border-radius: 5px;
        /* Optional rounded corners */
        /* background-color: #f8d7da;  */
    }
</style>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('salesagent.common.header')
            @include('salesagent.common.side_menu')
            @yield('content')
            @include('salesagent.common.footer')
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

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
    <script>
        function fetchNotifications() {
            $.ajax({
                url: '{{ route('notifications.index') }}',
                type: 'GET',
                success: function(data) {
                    // Clear existing notifications
                    $('#notificationList').empty();

                    // Update the notification counter
                    $('#notificationCounter').text(data.unreadCount);

                    // Check if there are no notifications
                    if (data.notifications.length === 0) {
                        $('#notificationList').append('<p class="no-notifications">No notifications!</p>');
                    } else {
                        // Append new notifications
                        data.notifications.forEach(function(notification) {
                            const timeAgo = moment(notification.created_at).fromNow();
                            $('#notificationList').append(`
                        <a href="#" id="notification-${notification.id}" class="dropdown-item ${notification.status ? '' : 'dropdown-item-unread'}" data-id="${notification.id}">
                            <span class="dropdown-item-avatar text-white">
                                <img alt="image" src="{{ asset('public/admin/assets/images/admin-image.jpg') }}" class="rounded-circle">
                            </span>
                            <span class="dropdown-item-desc">
                                <span class="message-user">Admin</span>
                                <span class="time messege-text">${notification.message}</span>
                                <span class="time">${timeAgo}</span>
                            </span>
                        </a>
                    `);
                        });
                    }

                    // Attach click event to mark individual notification as read
                    data.notifications.forEach(function(notification) {
                        $(`#notification-${notification.id}`).click(function(event) {
                            event.preventDefault();
                            var notificationId = $(this).data('id');

                            $.ajax({
                                url: `{{ route('notification.marked', ['notificationId' => ':notificationId']) }}`
                                    .replace(':notificationId', notificationId),
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(data) {
                                    toastr.success(data.message);
                                    fetchNotifications
                                (); // Refresh notifications after marking one as read
                                },
                                error: function(xhr) {
                                    toastr.error(
                                        'An error occurred while marking the notification as read.'
                                        );
                                }
                            });
                        });
                    });
                },
                error: function(xhr) {
                    console.error('An error occurred while fetching notifications.');
                }
            });
        }

        // Initial fetch
        fetchNotifications();

        // Periodically fetch notifications every 30 seconds
        setInterval(fetchNotifications, 1000);




        $('#markAllRead').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('notification.read') }}", // Adjust the URL as per your route setup
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    toastr.success(data.message);
                    fetchNotifications(); // Refresh notifications after marking all as read
                },
                error: function(xhr) {
                    toastr.error('An error occurred while marking notifications as read.');
                }
            });
        });
    </script>
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
