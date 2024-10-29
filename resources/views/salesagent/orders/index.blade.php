@extends('salesagent.layout.app')
@section('title', 'Order')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Orders</h4>
                                </div>
                            </div>
                            <div class="row col-12 mt-3 d-flex justify-content-start">
                                <div class="form-group col-sm-3 mb-2">
                                    <label for="periodSelect">Order Status</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="pending" selected>Pending</option>
                                        <option value="completed">Delivered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Order Id</th>
                                            <th>User Name</th>
                                            <th>User Email</th>
                                            <th>Commission</th>
                                            <th>Order Date</th>
                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    <tbody style="justify-content: center">
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
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('user-order.get') }}?status=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('user-order.get') }}?status=pending",
                    "type": "GET",
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
                        "data": "order_id"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return data.users.name;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return data.users.email;
                        }
                    },

                    {
                        "data": "product_commission",
                        "render": function(data, type, row) {
                            return '$' + data;
                        }
                    },

                    {
                        "data": "created_at",
                        "render": function(data, type, row) {
                            const createdAtDate = new Date(data);
                            const formattedDate = createdAtDate.toLocaleDateString();
                            const formattedTime = createdAtDate.toLocaleTimeString();
                            return `<div>${formattedDate}</div><div>${formattedTime}</div>`;
                        }
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data === "pending") {
                                return '<span style="color: red;">Pending</span>';
                            } else if (data === "completed") {
                                return '<span style="color: green;">Delivered</span>';
                            } else {
                                return data;
                            }
                        }
                    },




                ]
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteProductModal(id);
            });
            $('#example').on('click', '.viewSubadminBtn', function() {
                var id = $(this).data('id');
                viewOrderModal(id);
            });
        });
    </script>
    <script>
        // ############# Update Order Status #####
        $('#example').on('click', '.editSubadminBtn', function() {
            orderId = $(this).data('id');
            $('#editProduct').modal('show');
            $('#deliveredCheckbox').prop('checked', false);
            $('#pendingCheckbox').prop('checked', false);
            $.ajax({
                url: "{{ route('orders.status', ['id' => ':id']) }}".replace(':id',
                    orderId),
                type: 'GET',
                data: {
                    id: orderId
                },
                success: function(response) {
                    if (response.status == 'completed') {
                        $('#deliveredCheckbox').prop('checked', true);
                    } else if (response.status == 'pending') {
                        $('#pendingCheckbox').prop('checked', true);
                    }
                },
                error: function(jqXHR) {
                    var response = jqXHR.responseJSON;
                    toastr.error('An error occur!');
                }
            });
        });
        $(document).ready(function() {
            // Event listener for delivered checkbox
            $('#deliveredCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#pendingCheckbox').prop('checked', false); // Uncheck pending checkbox
                }
            });

            // Event listener for pending checkbox
            $('#pendingCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#deliveredCheckbox').prop('checked', false); // Uncheck delivered checkbox
                }
            });

            // Event handler for updating status
            $('#updateStatusBtn').click(function() {
                var status;
                if ($('#deliveredCheckbox').prop('checked')) {
                    status = 'completed';
                } else if ($('#pendingCheckbox').prop('checked')) {
                    status = 'pending';
                }

                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('orders.update-status', ['id' => ':id']) }}".replace(':id',
                        orderId),
                    type: 'POST',
                    data: {
                        id: orderId,
                        status: status,
                        _token: token
                    },
                    success: function(response) {
                        toastr.success('Order Status Updated Successfully!');
                        $('#editProduct').modal('hide');
                        reloadDataTable();
                    },
                    error: function(jqXHR, xhr) {
                        var response = jqXHR.responseJSON;

                        // Check if there is an error message in the response
                        if (response && response.error) {
                            toastr.error(response.error); // Display the server error message
                        } else {
                            toastr.error(
                                'An error occurred!'); // Fallback for unexpected errors
                        }
                    }
                });
            });
        });


        // ############# Delete Order ###########
        function deleteProductModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteProductModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteProduct(id)
            });
        });

        function deleteProduct(id) {
            $.ajax({
                url: "{{ route('order.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Order Deleted Successfully!');
                    $('#deleteProductModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endsection
