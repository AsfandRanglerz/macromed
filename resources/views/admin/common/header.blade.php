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
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="User Image"
                    src="{{ Auth::guard('admin')->check()
                        ? (Auth::guard('admin')->user()->image
                            ? asset(Auth::guard('admin')->user()->image)
                            : asset('public/admin/assets/images/admin-image.jpg'))
                        : (Auth::guard('web')->user()->image
                            ? asset(Auth::guard('web')->user()->image)
                            : asset('public/admin/assets/images/admin-image.jpg')) }}"
                    class="user-img-radious-style mt-2">
                <span class="d-sm-none d-lg-inline-block">
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">
                    {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : Auth::guard('web')->user()->name }}
                </div>
                <a href="{{ Auth::guard('admin')->check() ? url('admin/profile') : url('admin/profile') }}"
                    class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ Auth::guard('admin')->check() ? url('admin/logout') : url('admin/logout') }}"
                    class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>

</nav>
