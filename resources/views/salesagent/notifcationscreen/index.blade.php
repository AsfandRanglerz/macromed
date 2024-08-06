@extends('salesagent.layout.app')
@section('title', 'Sub Admins')
@section('content')



    <!-- Main Content -->
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="text-center">
                            <h2 class="mb-3">Notifications</h2>
                        </div>
                        <div class="container-fluid">
                            <div class="header">
                                <div class="col-12 col-md-12 text-right mr-2 mb-2">
                                    <button class="btn btn-primary markAllRead">Mark All As Read</button>
                                </div>
                            </div>
                            <div class="container mt-2">
                                <div id="notification-loader" class="notification-loader" style="display: none;"></div>
                                <div id="notification-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script>
        function fetchNotification() {
            $.ajax({
                url: '{{ route('notifications.index') }}', // Adjust the URL as per your route setup
                type: 'GET',
                success: function(data) {
                    $('.notification-loader').hide();
                    // Clear existing notifications
                    $('#notification-list').empty();

                    // Check if there are no notifications
                    if (data.notifications.length === 0) {
                        $('#notification-list').append('<p id="no-notifications">No notifications!</p>');
                    } else {
                        // Append new notifications
                        data.notifications.forEach(function(notification) {
                            const timeAgo = moment(notification.created_at).fromNow();
                            const unreadClass = notification.status === '1' ? '' :
                                'notification-item-unread';
                            $('#notification-list').append(`
                        <div class="notification-item ${unreadClass}">
                            <span class="notification-avatar">
                                <img alt="image" src="{{ asset('public/admin/assets/images/admin-image.jpg') }}" class="rounded-circle">
                            </span>
                            <span class="notification-desc">
                                <span class="notification-user">Admin</span>
                                <span class="notification-text">${notification.message}</span>
                                <span class="notification-time">${timeAgo}</span>
                            </span>
                        </div>
                    `);
                        });
                    }
                },
                error: function(xhr) {
                    $('.notification-loader').hide();
                    console.log('An error occurred while fetching notifications.');
                }


            });

        }
        fetchNotification();
        setInterval(fetchNotification, 1000);
    </script>
@endsection
