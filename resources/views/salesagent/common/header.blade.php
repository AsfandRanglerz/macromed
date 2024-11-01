<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            <li>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="bell"></i>
                <span class="badge headerBadge1" id="notificationCounter">0</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#" id="markAllRead" class="markAllRead">Mark All As Read</a>
                    </div>
                </div>
                <div id="notification-loader" class="notification-loader" style="display: none;"></div>
                <div class="dropdown-list-content dropdown-list-message notification-list" id="notificationList">
                    <!-- Notifications will be appended here -->
                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('notification.screen') }}">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>


        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image"
                    src="{{ asset(isset(Auth::guard('sales_agent')->user()->image) ? Auth::guard('sales_agent')->user()->image : 'public/admin/assets/images/admin-image.jpg') }}"
                    class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">{{ Auth::guard('sales_agent')->user()->name }}</div>
                <a href="{{ url('sales-agent/profile') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i>
                    Profile
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('sales-agent/logout') }}" class="dropdown-item has-icon text-danger"> <i
                            class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
            </div>
        </li>
    </ul>
</nav>
