@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 125px;margin-bottom: 35px">
        <h3>Welcome to Macromed</h3>
    </div>
    Dear {{ $data['username'] }},
    You have been blocked by the Admin due to the following reason:
    <div>
        The Reason are Following:
        <ul style="padding-left: 16px">
            <li><strong>Reason:</strong> {{ $data['reason'] }}</li>
            <li><strong>The Associated Email is:</strong> {{ $data['useremail'] }}</li>
        </ul>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Macromed
        </div>
    </div>
@endcomponent
