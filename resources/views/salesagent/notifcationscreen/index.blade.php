@extends('salesagent.layout.app')
@section('title', 'Sub Admins')
@section('content')

    <style>
        .notification-list {
            padding: 20px;
        }

        .notification-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            color: #333;
            background-color: #f9f9f9;
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
    </style>

    <!-- Main Content -->
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Notifications</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="notification-list" id="notification-list">
                                    @forelse ($notificationScreens as $notificationScreen)
                                        <div
                                            class="notification-item {{ $notificationScreen->status ? '' : 'notification-item-unread' }}">
                                            <span class="notification-avatar">
                                                <img alt="image"
                                                    src="{{ asset('public/admin/assets/images/admin-image.jpg') }}"
                                                    class="rounded-circle">
                                            </span>
                                            <span class="notification-desc">
                                                <span class="notification-user">Admin</span>
                                                <span class="notification-text">{!! $notificationScreen->message !!}</span>
                                                <span class="notification-time">
                                                    {{ \Carbon\Carbon::parse($notificationScreen->created_at)->diffForHumans() }}
                                                </span>
                                            </span>
                                        </div>
                                    @empty
                                        <p id="no-notifications">No notifications!</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
