@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row mb-3">
                @if (Auth::guard('admin')->check())
                    <!-- Admin View: Show Dashboard Statistics -->
                    <div class="col-xl-3 mb-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a style="text-decoration: none;" href="{{ route('customer.index') }}">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Customers</h5>
                                                    <h2 class="mb-3 font-18">{{ $customerCount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="{{ asset('public/admin/assets/panel/customers.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 mb-3 col-md-6 col-sm-6 col-xs-12">
                        <a style="text-decoration: none;" href="{{ route('salesagent.index') }}">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Sales Agents</h5>
                                                    <h2 class="mb-3 font-18">{{ $salesAgentCount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="{{ asset('public/admin/assets/panel/sales-agents.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 mb-3 col-md-6 col-sm-6 col-xs-12">
                        <a style="text-decoration: none;" href="{{ route('subadmin.index') }}">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Sub Admins</h5>
                                                    <h2 class="mb-3 font-18">{{ $subAdminCount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="{{ asset('public/admin/assets/panel/sub-admins.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 mb-3 col-md-6 col-sm-6 col-xs-12">
                        <a style="text-decoration: none;" href="{{ route('product.index') }}">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Total Products</h5>
                                                    <h2 class="mb-3 font-18">{{ $productCount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="{{ asset('public/admin/assets/panel/products.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @elseif(Auth::guard('web')->check())
                    <!-- Sub-admin View: Show Welcome Message -->
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h3 style="color: #fff !important;padding-top:20px;">Welcome, {{ Auth::guard('web')->user()->name }}!</h3>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

@endsection
