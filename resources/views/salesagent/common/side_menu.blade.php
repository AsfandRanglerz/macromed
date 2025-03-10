<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="https://macromed.com.pk/"> <img alt="image" src="{{ asset('public/admin/assets/images/logo-macromed.png') }}"
                    style="width: 40%" />
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- DashBoard --}}
            <li class="dropdown {{ request()->is('sales-agent/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/sales-agent/dashboard') }}" class="nav-link"><span><i
                            data-feather="home"></i>Dashboard</span></a>
            </li>
            {{-- Orders --}}
            <li class="dropdown {{ request()->is('sales-agent/user-order*') ? 'active' : '' }}">
                <a href="{{ route('user-order.index') }}" class="nav-link padding" style="padding-left: 27px">
                    <span> <i data-feather="shopping-cart"></i>
                        Orders</span>
                    <div id="orderUserCounter"
                        class="badge {{ request()->is('sales-agent/user-order*') ? 'bg-white text-dark' : 'bg-primary text-white' }} rounded-circle ">
                    </div>
                </a>
            </li>
            {{-- Reports --}}
            <li class="dropdown {{ request()->is('sales-agent/user-reports*') ? 'active' : '' }}">
                <a href="{{ route('user-reports.index') }}" class="nav-link">
                    <span><i data-feather="file-text"></i>Reports</span>
                </a>
            </li>

             {{-- With draw request --}}
             <li class="dropdown {{ request()->is('sales-agent/withdraw-request*') ? 'active' : '' }}">
                <a href="{{ route('user-request.index') }}" class="nav-link" style="padding-left:10px;">
                    <span><i class="fas fa-coins"></i>With Drawal Request</span>
                </a>
            </li>
            {{-- Private Notes --}}
            <li class="dropdown {{ request()->is('sales-agent/agentNotes*') ? 'active' : '' }}">
                <a href="{{ route('agentNotes.index') }}"
                    class="nav-link {{ request()->is('sales-agent/agentNotes*') ? 'text-white' : '' }}">
                    <span> <i data-feather="file"></i>Private Notes</span>
                </a>
            </li>
            {{-- <li class="dropdown {{ request()->is('admin/company*') ? 'active' : '' }}">
                <a href="{{ route('company.index') }}" class="nav-link"><i data-feather="users"></i><span>Company</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/officer*') ? 'active' : '' }}">
                <a href="{{ route('officer.index') }}" class="nav-link"><i data-feather="users"></i><span>Officer</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/about*') ? 'active' : '' }}">
                <a href="{{ route('about.index') }}" class="nav-link"><i data-feather="monitor"></i><span>About
                        Us</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i data-feather="monitor"></i><span>Privacy
                        Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Term&Condition</span></a>
            <li class="dropdown {{ request()->is('admin/faq*') ? 'active' : '' }}">
                <a href="{{ route('faq.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>FAQ's</span></a>
            </li> --}}
            </li>
        </ul>
    </aside>
</div>
