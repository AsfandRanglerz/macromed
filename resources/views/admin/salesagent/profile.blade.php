@extends('admin.layout.app')
@section('title', 'Profile')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="mb-0">{{ $salesManager->name }} Profile</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Image</th>
                                                <td>
                                                    @if ($salesManager->image)
                                                        <img src="{{ asset($salesManager->image) }}" class="rounded-circle"
                                                            alt="Profile Image" width="80px">
                                                    @else
                                                        <img src="{{ asset('public/admin/assets/images/users/admin.png') }}"
                                                            class="rounded-circle" alt="Default Profile Image"
                                                            width="80px">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Name</th>
                                                <td>{{ $salesManager->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td>{{ $salesManager->email }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phone</th>
                                                <td>{{ $salesManager->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Country</th>
                                                <td>{{ explode(',', $salesManager->country)[1] }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">State</th>
                                                <td>{{ explode(',', $salesManager->state)[1] }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">City</th>
                                                <td>{{ $salesManager->city }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Location</th>
                                                <td>{{ $salesManager->location }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">User Type</th>
                                                <td>
                                                    @if ($salesManager->user_type == 'salesmanager')
                                                        <p class="badge badge-success text-white">Sales Manager</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td>
                                                    @if ($salesManager->status === '0')
                                                        <p class="badge badge-danger text-white">In active</p>
                                                    @else
                                                        <p class="badge badge-success text-white">Active</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>

@endsection
