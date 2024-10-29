<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Macromed | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="{{ asset('public/admin/assets/bundles/lightgallery/dist/css/lightgallery.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/admin/toastr/toastr.css') }}">

    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

</head>
<style>
    .notification-list {
        padding: 5px;
    }

    #notification-list {
        max-height: 500px;
        overflow-y: auto;
        padding: 10px;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 10px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
        color: #333;
        background-color: #D3D3D3;
    }

    .notification-item:hover {
        background-color: #e2e2e2;
        transform: translateX(5px);
    }

    .notification-avatar {
        margin-right: 15px;
    }

    .notification-avatar img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
    }

    .notification-desc {
        flex-grow: 1;
    }

    .notification-user {
        font-weight: bold;
        color: #007bff;
    }

    .notification-text {
        font-size: 15px;
        color: #333;
        white-space: wrap;
        display: block;
    }

    .notification-time {
        font-size: 13px;
        color: #888;
    }

    .notification-item-unread {
        background-color: #eaf0f7;
        border-left: 5px solid #007bff;
    }

    #no-notifications {
        text-align: center;
        color: #dc3545;
        font-size: 1.2em;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #dc3545;
        border-radius: 5px;
        background-color: #f8d7da;
    }

    .notification-loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        display: block;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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
    <script src="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
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
        function updateOrderCounter() {
            $.ajax({
                url: "{{ route('user-orders.count') }}",
                type: 'GET',
                success: function(response) {
                    $('#orderUserCounter').text(response.count);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
        updateOrderCounter();
        setInterval(updateOrderCounter, 1000);

        function fetchNotifications() {

            $.ajax({
                url: '{{ route('notifications.index') }}', // Adjust the URL as per your route setup
                type: 'GET',
                success: function(data) {
                    $('.notification-loader').hide();
                    // Clear existing notifications
                    $('#notificationList').empty();

                    // Update the notification counter
                    $('#notificationCounter').text(data.unreadCount);

                    // Check if there are no notifications
                    if (data.notifications.length === 0) {
                        $('#notificationList').append('<p id="no-notifications">No notifications!</p>');
                    } else {
                        // Append new notifications
                        data.notifications.forEach(function(notification) {
                            const timeAgo = moment(notification.created_at).fromNow();

                            // Truncate message to 15 words
                            const truncatedMessage = notification.message.split(' ').slice(0, 5).join(
                                ' ') + (notification.message.split(' ').length > 15 ? '...' : '');
                            $('#notificationList').append(`
                        <div id="drop-item-${notification.id}" class="notify-item notification-item ${notification.status ==='1' ? '' : 'notification-item-unread'}" data-id="${notification.id}">
                            <span class="notification-avatar text-white">
                                <img alt="image" src="{{ asset('public/admin/assets/images/admin-image.jpg') }}" class="rounded-circle">
                            </span>
                            <span class="notification-desc">
                                <span class="notification-user">Admin</span>
                                <span class="notification-text">${truncatedMessage}</span>
                                <span class="notification-time">${timeAgo}</span>
                            </span>
                        </div>
                    `);
                        });
                    }

                    // Attach click event to mark individual notification as read
                    $('.notify-item').click(function(event) {
                        event.preventDefault();
                        var notificationId = $(this).data('id');

                        $.ajax({
                            url: `{{ route('notification.marked', ['notificationId' => ':notificationId']) }}`
                                .replace(':notificationId', notificationId),
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                toastr.success(data.message);
                                fetchNotifications();
                            },
                            error: function(xhr) {
                                toastr.error(
                                    'An error occurred while marking the notification as read.'
                                );
                            }
                        });
                    });
                },
                error: function(xhr) {
                    $('.notification-loader').hide();
                    console.log('An error occurred while fetching notifications.');
                }
            });
        }
        // Initial fetch
        fetchNotifications();
        // Periodically fetch notifications every 30 seconds
        setInterval(fetchNotifications, 1000);

        $('.markAllRead').click(function(event) {
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
