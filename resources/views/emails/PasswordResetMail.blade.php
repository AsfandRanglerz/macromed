@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 125px;margin-bottom: 35px">
        <h3>Welcome to Macromed</h3>
    </div>
    We have received reset password request, please click below button to reset password.
    @component('mail::button', ['url' => $detail['url']])
        Reset Password
    @endcomponent

    Thanks
    {{ config('app.name') }}
@endcomponent
