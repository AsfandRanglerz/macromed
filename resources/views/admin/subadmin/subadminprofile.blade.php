@extends('admin.layout.app')
@section('title', 'index')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="mb-0">{{ $subAdmin->name }} Profile</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Image</th>
                                                <td>
                                                    @if ($subAdmin->image)
                                                        <img src="{{ asset($subAdmin->image) }}" class="rounded-circle"
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
                                                <td>{{ $subAdmin->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td>{{ $subAdmin->email }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phone</th>
                                                <td>{{ $subAdmin->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">User Type</th>
                                                <td>
                                                    @if ($subAdmin->user_type == 'subadmin')
                                                        <p class="badge badge-success text-white">Sub Admin</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td>
                                                    @if ($subAdmin->status === '0')
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
