<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" style="width: 50%" />
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- DashBoard --}}
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="home"></i><span>Dashboard</span></a>
            </li>
            {{-- User Managment --}}
            @if (
                (auth()->guard('web')->check() &&
                    (auth()->guard('web')->user()->can('subadmins') ||
                        auth()->guard('web')->user()->can('users') ||
                        auth()->guard('web')->user()->can('Teams') ||
                        auth()->guard('web')->user()->can('Users Coupon Code'))) ||
                    auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>User
                            Managment</span></a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/subadmin*') || request()->is('admin/user*') || request()->is('admin/teams*') || request()->is('admin/couponCode*') ? 'show' : '' }}">
                        {{-- Roles & Permissions --}}
                        {{-- @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subadmins'))
                            <li class="{{ request()->is('admin/subadmin') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin') ? 'text-white' : '' }}"><i
                                        data-feather="user"></i><span>Sub
                                        Admins</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check()) --}}
                        <li class="{{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                            <a href="{{ route('subadmin.index') }}"
                                class="nav-link {{ request()->is('admin/subadmin*') ? 'text-white' : '' }}"><i
                                    data-feather="user"></i><span>Sub
                                    Admins</span></a>
                        </li>
                        {{-- @endif --}}

                    </ul>
                </li>
            @endif
            {{-- About Us --}}
            {{-- <li class="dropdown {{ request()->is('admin/about*') ? 'active' : '' }}">
                <a href="{{ route('about.index') }}" class="nav-link"><i data-feather="monitor"></i><span>About
                        Us</span></a>
            </li> --}}
            {{-- Privacy Policy --}}
            {{-- <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i data-feather="monitor"></i><span>Privacy
                        Policy</span></a>
            </li> --}}
            {{-- Terms & Cond --}}
            {{-- <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Term&Condition</span></a>

            </li> --}}
        </ul>
    </aside>
</div>
