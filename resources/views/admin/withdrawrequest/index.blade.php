@extends('admin.layout.app')
@section('title', 'PaymentRequest')
@section('content')
    <style>
        /* Define a custom color class */
        .loader-custom-color {
            color: #2A9BDF;
            /* Red color */
        }
    </style>
    <!-- Edit PaymentRequest Modal -->
    <div class="modal fade" id="editPaymentRequestModal" tabindex="-1" role="dialog"
        aria-labelledby="editPaymentRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentRequestModalLabel">Send Transaction Screen Short</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPaymentRequest" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control name text-center" name="name" required
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Bank Name</label>
                                    <input type="text" class="form-control name text-center bank_name" name="name"
                                        required disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Account Holder Name</label>
                                    <input type="text" class="form-control name text-center bank_holder_name"
                                        name="name" required disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Account Number</label>
                                    <input type="text" class="form-control name text-center account_number"
                                        name="name" required disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mt-4">
                            <div class="form-group">
                                <label for="image">Upload Transaction Screen Short</label>
                                <input name="image" type="file" class="form-control image" id="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="updatePaymentRequests()">Send</button>
                </div>
            </div>
        </div>
    </div>


    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Withdrawal Requests</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                {{-- <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createPaymentRequestModal">
                                    Create Payment Request
                                </a> --}}
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Total Amount</th>
                                            <th>Requested Amount</th>
                                            {{-- <th>Account Details</th> --}}
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('js')
    {{-- Data Table --}}
    <script>
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('paymentRequest.get') }}",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return data.sales_agent.name;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return data.sales_agent.email;
                        }
                    },
                    {
                        "data": 'sales_agent.agent_wallet.recevied_commission',
                        "render": function(data, type, row) {
                            var amount = parseFloat(data);
                            if (amount % 1 === 0) {
                                return '$' + amount.toFixed(0);
                            } else {
                                return '$' + amount.toFixed(
                                    2);
                            }
                        }

                    },
                    {
                        "data": 'amount',
                        "render": function(data, type, row) {
                            var amount = parseFloat(data);
                            if (amount % 1 === 0) {
                                return '$' + amount.toFixed(0);
                            } else {
                                return '$' + amount.toFixed(
                                    2);
                            }
                        }
                    },

                    // {
                    //     "render": function(data, type, row) {
                    //         return '<a href="' +
                    //             "{{ route('paymentAccount.index', ['userId' => ':id']) }}"
                    //             .replace(':id', row.user_id) +
                    //             '" class="btn btn-primary mb-0 text-white"><i class="fas fa-user"></i></a>';
                    //     },
                    // },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data == 'requested') {
                                return '<span class="text-danger">Requested</span>';
                            } else {
                                return '<span class="text-success">Paid</span>';
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-primary mb-0 mr-3 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>';
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editPaymentRequestModal(id);
            });
        });
    </script>

    <script>
        // ######Get & Update PaymentRequest#########
        var showPaymentRequest = '{{ route('paymentRequest.show', ':id') }}';
        var updatePaymentRequest = '{{ route('paymentRequest.update', ':id') }}';

        function editPaymentRequestModal(id) {
            $.ajax({
                url: showPaymentRequest.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    var fullName = response.sales_agent.name;
                    $('#editPaymentRequest .name').val(fullName);
                    $('#editPaymentRequest .bank_name').val(response.sales_agent.agent_accounts.account_name);
                    $('#editPaymentRequest .bank_holder_name').val(response.sales_agent.agent_accounts
                        .account_holder_name);
                    $('#editPaymentRequest .account_number').val(response.sales_agent.agent_accounts
                        .account_number);

                    $('#editPaymentRequestModal').modal('show');
                    $('#editPaymentRequestModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        function updatePaymentRequests() {
            var id = $('#editPaymentRequestModal').data('id');
            var formData = new FormData($('#editPaymentRequest')[0]);

            // Disable the button
            $('#editPaymentRequestModal button[type="button"]').prop('disabled', true);


            // Show loader with custom color
            var loaderHtml =
                '<div class="spinner-border loader-custom-color" role="status"><span class="sr-only">Loading...</span></div>';

            $('#editPaymentRequestModal .modal-footer').html(loaderHtml);

            $.ajax({
                url: updatePaymentRequest.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Transaction Screen Short Sent Successfully!')
                    $('#editPaymentRequestModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                },
                complete: function() {
                    // Enable the button
                    $('#editPaymentRequestModal button[type="button"]').prop('disabled', false);

                    // Restore button content
                    var buttonHtml =
                        '<button type="button" class="btn btn-danger" onclick="updatePaymentRequests()">Update</button>';
                    $('#editPaymentRequestModal .modal-footer').html(buttonHtml);
                }
            });
        }
    </script>


@endsection
