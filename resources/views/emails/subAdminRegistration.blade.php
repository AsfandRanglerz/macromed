@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 125px;margin-bottom: 35px">
        <h3>Welcome to Macromed</h3>
    </div>
    Dear {{ $data['subadminname'] }},
    Welcome to Macromed! Your account has been created successfully by the Admin.
    <div>
        Here are your account details:
        <ul style="padding-left: 16px">
            <li><strong>Email:</strong> {{ $data['subadminemail'] }}</li>
            <li><strong>Password:</strong> {{ $data['password'] }}</li>
        </ul>
        <div>
            <p style="width: 160px;margin:auto"><a href="{{ url('/admin') }}"
                    style="padding:5px 10px;color:rgb(253, 253, 253);background:#7bab13;border-radius:5px;text-decoration:none">Click
                    here to Login </a></p>
        </div>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Macromed
        </div>
    </div>
@endcomponent
