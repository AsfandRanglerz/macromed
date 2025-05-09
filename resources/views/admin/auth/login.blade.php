@extends('admin.auth.layout.app')
@section('title', 'Login')
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="text-center mt-1">
                            <img alt="image" src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" style="width: 40%" />
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('admin/login')}}" class="needs-validation" novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus name="email">
                                    @error('email')
                                    <span class="text-danger">Email required</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <div class="position-relative">
                                        <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                        <span class="position-absolute eye-icon-set" style="cursor: pointer;">
                                            <i id="eye" class="eye-height" data-feather="eye-off"></i>
                                        </span>
                                    </div>


                                    @error('password')
                                    <span class="text-danger">{{$errors->first('password')}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        {{-- <input type="checkbox" class="custom-control-input" tabindex="3" id="remember-me" name="remember"> --}}
                                        <div class="d-block">
                                            {{-- <label class="custom-control-label" for="remember-me">Remember Me</label> --}}
                                            <div class="float-right">
                                                <a href="{{url('admin-forgot-password')}}" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-login" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')

<script>
    $(document).ready(function () {
        $('.btn-login').on('click', function () {
            $('.eye-icon-set').addClass('d-none');
        });

        feather.replace();

        $('.eye-icon-set').on('click', function () {
            const passwordField = $('#password');
            const icon = $('#eye');

            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.attr('data-feather', 'eye');
            } else {
                passwordField.attr('type', 'password');
                icon.attr('data-feather', 'eye-off');
            }

            feather.replace();
        });
    });
</script>

@endsection
