@extends('salesagent.layout.app')
@section('title', 'Profile')
@section('content')

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="padding-20">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                                            aria-selected="false">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                                            role="tab" aria-selected="true">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                                        <div class="row">
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Full Name</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->name }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->phone }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->email }}</p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade active show" id="settings" role="tabpanel"
                                        aria-labelledby="profile-tab2">
                                        <form method="post" action="{{ url('sales-agent/update-profile') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-header">
                                                <h4>Edit Profile</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name" value="{{ $data->name }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" name="email" value="{{ $data->email }}"
                                                            class="form-control">

                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Profile Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image" class="custom-file-input"
                                                                id="customFile">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>
                                                        <img src="{{ asset($data->image) }}" alt="Profile Image"
                                                            width="100" class="mt-2">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Phone</label>
                                                        <input type="number" name="phone" value="{{ $data->phone }}"
                                                            class="form-control">

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Country</label>
                                                        <input type="text" name="country"
                                                            value="{{ explode(',', $data->country)[1] }}"
                                                            class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>State</label>
                                                        <input type="text" name="state"
                                                            value="{{ explode(',', $data->state)[1] }}" class="form-control"
                                                            disabled>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>City</label>
                                                        <input type="text" name="city" value="{{ $data->city }}"
                                                            class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Location</label>
                                                        <input type="text" name="location" value="{{ $data->location }}"
                                                            class="form-control">

                                                    </div>
                                                </div>

                                                <h4>Account Details</h4>
                                                @if (isset($data->salesAgent) && count($data->salesAgent) > 0)
                                                    @foreach ($data->salesAgent as $agent)
                                                        <div class="row">
                                                            <div class="form-group col-md-4 col-12">
                                                                <label>Account Name</label>
                                                                <input type="text" name="account_name"
                                                                    value="{{ $agent->account_name }}"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group col-md-4 col-12">
                                                                <label>Account Holder Name</label>
                                                                <input type="text" name="account_holder_name"
                                                                    value="{{ $agent->account_holder_name }}"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group col-md-4 col-12">
                                                                <label>Account Number</label>
                                                                <input type="text" name="account_number"
                                                                    value="{{ $agent->account_number }}"
                                                                    class="form-control">

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <h4>Change Password</h4>


                                                        <div class="row">
                                                            <div class="form-group col-md-4 col-12">
                                                                <label>Old Password</label>
                                                                <input type="password" id="old_password" name="old_password"

                                                                    class="form-control">
                                                                    <span class="fa fa-eye-slash position-absolute toggle-password" data-target="old_password"
                                                                    style="top: 3.18rem; right: 1.4rem; cursor: pointer;"></span>
                                                                </div>

                                                                <div class="form-group col-md-4 col-12">
                                                                    <label>New Password</label>
                                                                    <input type="password" id="new_password" name="new_password"

                                                                    class="form-control">
                                                                    <span class="fa fa-eye-slash position-absolute toggle-password" data-target="new_password"
                                                                    style="top: 3.18rem; right: 1.4rem; cursor: pointer;"></span>
                                                            </div>


                                                        </div>

                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>

                                    </div>
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

<script>
    @if ($errors->any())
    @foreach ($errors->all() as $error)
    toastr.error('{{ $error }}');
    @endforeach
    @endif
    </script>

    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const passwordField = document.getElementById(this.getAttribute('data-target'));
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle eye icon
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        });
    </script>
@endsection
