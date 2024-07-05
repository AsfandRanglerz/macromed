<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="{{ asset('public/admin/assets/images/logo-macromed.png') }}"
                    style="width: 40%" />
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- DashBoard --}}
            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        data-feather="home"></i><span>Dashboard</span></a>
            </li>
            {{-- Inventory Managment --}}
            @if (
                (auth()->guard('web')->check() &&
                    (auth()->guard('web')->user()->can('category') ||
                        auth()->guard('web')->user()->can('subcategory') ||
                        auth()->guard('web')->user()->can('products') ||
                        auth()->guard('web')->user()->can('Cash Back Per Order') ||
                        auth()->guard('web')->user()->can('Bucket Commission') ||
                        auth()->guard('web')->user()->can('productsize'))) ||
                    auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="layout"></i><span>Inventory
                            Managment</span></a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/category*') || request()->is('admin/subCategory*') || request()->is('admin/brands*') || request()->is('admin/product*') || request()->is('admin/company*') || request()->is('admin/bucketCommission*') ? 'show' : '' }}">
                        {{-- Category --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('category'))
                            <li class="dropdown {{ request()->is('admin/category') ? 'active' : '' }}">
                                <a href="{{ route('category.index') }}"
                                    class="nav-link {{ request()->is('admin/category') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Category</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/category') ? 'active' : '' }}">
                                <a href="{{ route('category.index') }}"
                                    class="nav-link {{ request()->is('admin/category') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Category</span>
                                </a>
                            </li>
                        @endif
                        {{-- Sub Category --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subcategory'))
                            <li class="dropdown {{ request()->is('admin/subCategory*') ? 'active' : '' }}">
                                <a href="{{ route('subCategory.index') }}"
                                    class="nav-link {{ request()->is('admin/subCategory*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Sub Category</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/subCategory*') ? 'active' : '' }}">
                                <a href="{{ route('subCategory.index') }}"
                                    class="nav-link {{ request()->is('admin/subCategory*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Sub Category</span>
                                </a>
                            </li>
                        @endif
                        {{-- Brands --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subcategory'))
                            <li class="dropdown {{ request()->is('admin/brands*') ? 'active' : '' }}">
                                <a href="{{ route('brands.index') }}"
                                    class="nav-link {{ request()->is('admin/brands*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Brands</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/brands*') ? 'active' : '' }}">
                                <a href="{{ route('brands.index') }}"
                                    class="nav-link {{ request()->is('admin/brands*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Brands</span>
                                </a>
                            </li>
                        @endif
                        {{-- Company --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subcategory'))
                            <li class="dropdown {{ request()->is('admin/company*') ? 'active' : '' }}">
                                <a href="{{ route('company.index') }}"
                                    class="nav-link {{ request()->is('admin/company*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Company</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/company*') ? 'active' : '' }}">
                                <a href="{{ route('company.index') }}"
                                    class="nav-link {{ request()->is('admin/company*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Company</span>
                                </a>
                            </li>
                        @endif
                        {{-- Modals --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subcategory'))
                            <li class="dropdown {{ request()->is('admin/models*') ? 'active' : '' }}">
                                <a href="{{ route('models.index') }}"
                                    class="nav-link {{ request()->is('admin/models*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Models</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/models*') ? 'active' : '' }}">
                                <a href="{{ route('models.index') }}"
                                    class="nav-link {{ request()->is('admin/models*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Models</span>
                                </a>
                            </li>
                        @endif
                        {{-- Certifications --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('subcategory'))
                            <li class="dropdown {{ request()->is('admin/certification*') ? 'active' : '' }}">
                                <a href="{{ route('certification.index') }}"
                                    class="nav-link {{ request()->is('admin/certification*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Certifications</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/certification*') ? 'active' : '' }}">
                                <a href="{{ route('certification.index') }}"
                                    class="nav-link {{ request()->is('admin/certification*') ? 'text-white' : '' }}">
                                    <i data-feather="layers"></i><span>Certifications</span>
                                </a>
                            </li>
                        @endif
                        {{-- Products --}}
                        {{-- @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('products'))
                            <li class="dropdown {{ request()->is('admin/product*') ? 'active' : '' }}">
                                <a href="{{ route('product.index') }}"
                                    class="nav-link {{ request()->is('admin/product*') ? 'text-white' : '' }}">
                                    <i data-feather="box"></i><span>Products</span>
                                </a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/product*') ? 'active' : '' }}">
                                <a href="{{ route('product.index') }}"
                                    class="nav-link {{ request()->is('admin/product*') ? 'text-white' : '' }}">
                                    <i data-feather="box"></i><span>Products</span>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
            @endif
            {{-- User Managment --}}
            {{-- @if ((auth()->guard('web')->check() && (auth()->guard('web')->user()->can('Sub Admins') || auth()->guard('web')->user()->can('users'))) || auth()->guard('admin')->check()) --}}
            {{-- <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>User
                            Managment</span></a> --}}
            {{-- <ul class="dropdown-menu {{ request()->is('admin/subadmin*') || request()->is('admin/user*') || request()->is('admin/salesagent*') ? 'show' : '' }}"> --}}
            {{-- Roles & Permissions --}}
            {{-- @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Sub Admins'))
                            <li class="{{ request()->is('admin/subadmin') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin') ? 'text-white' : '' }}"><i
                                        data-feather="user"></i><span>Sub
                                        Admins</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="{{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin*') ? 'text-white' : '' }}"><i
                                        data-feather="user"></i><span>Sub
                                        Admins</span></a>
                            </li>
                        @endif
                        <li class="{{ request()->is('admin/salesagent*') ? 'active' : '' }}">
                            <a href="{{ route('salesagent.index') }}"
                                class="nav-link {{ request()->is('admin/salesagent*') ? 'text-white' : '' }}"><i
                                    data-feather="user"></i><span>Sales Managers</span></a>
                        </li> --}}
            {{-- </ul>
                </li>
            @endif --}}
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
