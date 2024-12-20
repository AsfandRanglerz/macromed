@extends('admin.layout.app')
@section('title', 'Invoice')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('order.index') }}">Back</a>
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <h2>Invoice</h2>
                                    <div class="invoice-number">Order #{{ $orders->order_id }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Billed To:</strong><br>
                                            @if ($orders->billing_address == null)
                                                <span class="text-danger">No Billing Address Found!</span>
                                            @else
                                                {!! str_replace(',', '<br>', e($orders->billing_address)) !!}<br>
                                            @endif
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Shipped To:</strong><br>
                                            {!! str_replace(',', '<br>', e($orders->address)) !!}<br>
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Payment Method:</strong><br>
                                            {{ $orders->payment_type === 'cash on delivery' ? 'Cash on Delivery' : 'Online' }}
                                            ending **** {{ substr($orders->card_number, -4) }}<br>
                                            {{ $orders->users->email }}
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Order Date:</strong><br>
                                            {{ $orders->created_at->format('F d, Y') }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title">Order Summary</div>
                                <p class="section-lead">All items here cannot be deleted.</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th>Item</th>
                                            <th class="text-center">Actual Price</th>
                                            <th class="text-center">Product Disc.</th>
                                            <th class="text-center">Category Disc.</th>
                                            <th class="text-center">Brand Disc.</th>
                                            <th class="text-center">Total Disc.</th>
                                            <th class="text-center">Discount Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Totals</th>
                                        </tr>

                                        @foreach ($orders->orderItem as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->variant_number }}</td>
                                                <td class="text-center">Rs{{ number_format($item->price, 2) }}</td>

                                                <td class="text-center">
                                                    @if ($item->product_discount != 0.0)
                                                        %{{ number_format($item->product_discount, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->category_discount != 0.0)
                                                        %{{ number_format($item->category_discount, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->brand_discount != 0.0)
                                                        %{{ number_format($item->brand_discount, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->total_discount != 0.0)
                                                        %{{ number_format($item->total_discount, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>


                                                <!-- Check if discounted_price is not null -->
                                                <td class="text-center">
                                                    @if ($item->discounted_price != 0.0)
                                                       Rs {{ number_format($item->discounted_price, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">
                                                   Rs {{ number_format($item->subtotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8">
                                        <div class="section-title">Payment Method</div>
                                        <p class="section-lead">The payment method that we provide is to make it easier for
                                            you to pay invoices.</p>
                                        <div class="images">
                                            <img src="{{ asset('public/admin/assets/img/cards/visa.png') }}"
                                                alt="visa">
                                            <img src="{{ asset('public/admin/assets/img/cards/jcb.png') }}" alt="jcb">
                                            <img src="{{ asset('public/admin/assets/img/cards/mastercard.png') }}"
                                                alt="mastercard">
                                            <img src="{{ asset('public/admin/assets/img/cards/paypal.png') }}"
                                                alt="paypal">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Subtotal</div>
                                            <div class="invoice-detail-value">
                                                Rs {{ number_format(
                                                    $subtotal = $orders->orderItem->sum(function ($item) {
                                                        return $item->subtotal;
                                                    }),
                                                    2,
                                                ) }}
                                            </div>
                                        </div>

                                        @if ($orders->discount_code)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Coupon Code Discount
                                                    ({{ $orders->dicount_code_percentage }}%)</div>
                                                <div class="invoice-detail-value">
                                                    -Rs
                                                    {{ number_format($subtotal * ($orders->dicount_code_percentage / 100), 2) }}
                                                </div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Total after Discount</div>
                                                <div class="invoice-detail-value">
                                                    {{ number_format($subtotal - $subtotal * ($orders->dicount_code_percentage / 100), 2) }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="invoice-detail-item">

                                                <div class="invoice-detail-value">
                                                    No Coupon Code Discound Found!
                                                </div>
                                            </div>
                                        @endif


                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">
                                                Rs {{ number_format($orders->shipping_amount ?? 0, 2) }}
                                            </div>
                                        </div>

                                        <hr class="mt-2 mb-2">

                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                Rs
                                                {{ number_format(
                                                    ($orders->discounted_total != 0 ? $orders->discounted_total : $orders->total) + ($orders->shipping_amount ?? 0),
                                                    2,
                                                ) }}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-danger btn-icon icon-left" id="printInvoice"><i class="fas fa-print"></i>
                    Print</button>
            </div>
        </section>
    </div>

@endsection
