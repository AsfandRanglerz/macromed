@extends('salesagent.layout.app')
@section('title', 'History')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <a class="btn btn-primary mb-3" href="{{ route('user-request.index') }}">Back</a>
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Withdrawal History</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive datatables-basic table border-top  table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction proof</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>{{ $paymentRequests->updated_at->format('d-m-Y') }}</td>
                                            <td>
                                                <div id="aniimated-thumbnials" class=" clearfix">
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <a href="{{ asset($paymentRequests->image) }}"
                                                            data-sub-html="Payment Proof Image">
                                                            <img src="{{ asset($paymentRequests->image) }}"
                                                                class="img-responsive thumbnail" alt="Payment Proof Image"
                                                                style="max-width: 100px; max-height: 100px;">
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@section('js')
    {{-- Data Table --}}


@endsection
@endsection
