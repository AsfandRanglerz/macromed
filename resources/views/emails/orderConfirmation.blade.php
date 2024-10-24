@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="App Icon"
            style="vertical-align: middle; margin-bottom: 35px; height: 125px;">
        <h3>Welcome to Macromed</h3>
    </div>
    <div style="align-items: center">
        <p style="font-size: 24px; margin-bottom: 10px;">Congratulations!</p>
        <p style="font-size: 16px;">{{ $data['username'] }}</p>
        <p style="font-size: 16px;">
            Your order has been placed!
        </p>
        <div>
            Here Is Your Order Code:
            <ul style="padding-left: 16px;">
                <li><strong>Order Id:</strong> {{ $data['ordercode'] }}</li>
            </ul>
            <div>
                <p style="font-size: 16px; margin-top: 10px;">
                    Your Order Total: Rs: {{ $data['total_amount'] }}
                </p>
            </div>
        </div>
    </div>
@endcomponent
