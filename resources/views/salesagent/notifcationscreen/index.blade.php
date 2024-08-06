@extends('salesagent.layout.app')
@section('title', 'Sub Admins')
@section('content')



    <!-- Main Content -->
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <button class="btn btn-primary markAllRead">Mark All As Read</button>
                                </div>
                            </div>
                            <div class="container mt-2">
                                <div id="notification-list">
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
@section('js')
@endsection
