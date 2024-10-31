@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 125px;margin-bottom: 35px">
        <h3>Welcome to Macromed</h3>
    </div>
    <div style="align-items: center">
        <p style="font-size: 24px; margin-bottom: 10px;">Withdrawal Request Approval</p>
        <p style="font-size: 16px; ">Hello {{ $data['username'] }} </p>
        <p style="font-size: 16px; ">
            Your Withdrawal Request Has Been Approved.
        </p>
        <p style="font-size: 16px; ">
            Here is the Proof:
        </p>
        <img src="{{ asset($data['image']) }}" alt="Payment Request Proof Image" style="max-width: 100%; margin-top: 10px;">
        <div>
            <p style="font-size: 16px;  margin-top: 10px;">
                Requested Amount: ${{ $data['amount'] }}
            </p>
        </div>
    </div>
@endcomponent
