@extends('salesagent.layout.app')
@section('title', 'Sub Admins')
@section('content')

    <style>
        .notification-list {
            padding: 20px;
        }

        .dropdown-item {
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

        .dropdown-item:hover {
            background-color: #e2e2e2;
            transform: translateX(5px);
        }

        .dropdown-item-avatar {
            margin-right: 15px;
        }

        .dropdown-item-avatar img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
        }

        .dropdown-item-desc {
            flex-grow: 1;
        }

        .message-user {
            font-weight: bold;
            color: #007bff;
        }

        .message-text {
            font-size: 15px;
            color: #333;
            white-space: wrap;
            display: block
        }

        .time {
            font-size: 13px;
            color: #888;
        }

        .dropdown-item-unread {
            background-color: #eaf0f7;
            border-left: 5px solid #007bff;
        }

        .no-notifications {
            text-align: center;
            color: #888;
            font-size: 16px;
            padding: 20px;
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
                                <div class="notification-list">
                                    @forelse ($notificationScreens as $notificationScreen)
                                        <div class="dropdown-item">
                                            <span class="dropdown-item-avatar">
                                                <img alt="image"
                                                    src="{{ asset('public/admin/assets/images/admin-image.jpg') }}"
                                                    class="rounded-circle">
                                            </span>
                                            <span class="dropdown-item-desc">
                                                <span class="message-user">Admin</span>
                                                <span class="message-text">{!! $notificationScreen->message !!}</span>
                                                <span
                                                    class="time">{{ \Carbon\Carbon::parse($notificationScreen->created_at)->diffForHumans() }}</span>
                                            </span>
                                        </div>
                                    @empty
                                        <p class="no-notifications">No notifications!</p>
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
