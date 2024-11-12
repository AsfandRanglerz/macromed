@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle; margin-bottom: 35px; height: 125px;">
        <h3>New Contact Us Message</h3>
    </div>
    <div style="align-items: center">
        <p style="font-size: 24px; margin-bottom: 10px;">Hello Admin,</p>
        <div>
            <p style="font-size: 16px; margin-top: 10px;">
                <strong>User Email:</strong> {{ $data['email'] }}
            </p>
            <p style="font-size: 16px; margin-top: 10px;">
                <strong>Message:</strong>
            </p>
            <p style="font-size: 16px; padding: 10px; background-color: #f9f9f9; border: 1px solid #e1e1e1;">
                {{ $data['message'] }}
            </p>
        </div>
    </div>
@endcomponent
