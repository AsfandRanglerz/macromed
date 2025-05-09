@extends('admin.layout.app')
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
                                        <option value="pending" selected><span class="text-danger">Pending</span></option>
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
                                            <th>Sales Agent ID</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Sales Agent Commission</th>
                                            <th>Total Amount</th>
                                            <th>Discounted Total</th>
                                            <th>Order Date</th>
                                            <th>Order Detail</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
    <!-- Change Status Order Modal -->
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="editProductLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabel">Change Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="delivered" id="deliveredCheckbox">
                        <label class="form-check-label" for="deliveredCheckbox">
                            Delivered
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="pending" id="pendingCheckbox">
                        <label class="form-check-label" for="pendingCheckbox">
                            Pending
                        </label>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" id="updateStatusBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Order Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are You Sure You Want To Delete This Order?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Agent Modal --}}
    <!-- Modal -->
    <div class="modal fade" id="selectSalesAgentModal" tabindex="-1" aria-labelledby="selectSalesAgentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectSalesAgentModalLabel">Select Sales Agent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="salesAgentSelect">Choose a Sales Agent:</label>
                    <select id="salesAgentSelect" class="form-control">
                        @foreach ($salesAgents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer text-center justify-content-center">
                    <button type="button" class="btn btn-primary" id="confirmSalesAgent">Confirm Agent</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    {{-- Data Table --}}
    <script>
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('order.get') }}?status=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('order.get') }}?status=pending",
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
                            if (data.sales_agent  && data.sales_agent.id_number) {
                                return data.sales_agent.id_number;
                            } else {
                                return '<span class="text-danger">No Agent Found!</span>';
                            }

                        }
                    },
                    {
                        "data": "country"
                    },
                    {
                        "data": "state"
                    },
                    {
                        "data": "city"
                    },
                    {
                        "data": "product_commission",
                        "render": function(data, type, row) {
                            return 'Rs ' + data;
                        }
                    },
                    {
                        "data": "total",
                        "render": function(data, type, row) {
                            return 'Rs ' + parseFloat(data).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },

                    {
                        "data": "discounted_total",
                        "render": function(data, type, row) {
                            if (data) {
                                return 'Rs ' + parseFloat(data).toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            } else {
                                return '<span class="text-danger">No Discount Total Found!</span>'
                            }

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
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('invoice.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary text-white"><i class="fas fa-user"></i></a>';
                        },
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

                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data === "pending") {
                                return `
        <div class="dropdown d-inline">
            <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton${row.id}">
                <a class="dropdown-item has-icon editSubadminBtn" href="#" data-id="${row.id}"><i class="fas fa-edit"></i>Edit</a>
                <a class="dropdown-item has-icon deleteSubadminBtn" href="#" data-id="${row.id}"><i class="fas fa-trash-alt"></i>Delete</a>
            </div>
        </div>
        `;
                            } else {
                                return '<span class="text-muted">No actions available for delivered orders</span>';
                            }
                        }
                    }


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
            $('#deliveredCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#pendingCheckbox').prop('checked', false);
                }
            });
            $('#pendingCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#deliveredCheckbox').prop('checked', false);
                }
            });
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
                        if (response.status === 'error') {
                            $('#selectSalesAgentModal').modal('show');
                            $('#editProduct').modal('hide');
                        } else {
                            toastr.success('Order Status Updated Successfully!');
                            $('#editProduct').modal('hide');
                            reloadDataTable();
                        }
                    },
                    error: function(jqXHR, xhr) {
                        var response = jqXHR.responseJSON;
                        if (response && response.error) {
                            toastr.error(response.error);
                        } else {
                            toastr.error(
                                'An error occurred!');
                        }
                    }
                });
            });


        });

        $('#confirmSalesAgent').on('click', function() {
            const selectedAgentId = $('#salesAgentSelect').val();
            if (selectedAgentId) {
                $.ajax({
                    url: "{{ route('salesAgent.status', ['id' => ':id']) }}".replace(':id',
                        orderId),
                    type: 'POST',
                    data: {
                        id: orderId,
                        sales_agent_id: selectedAgentId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.alert === 'success') {
                            $('#selectSalesAgentModal').modal('hide');
                            toastr.success('Agent assigned successfully');
                            $('#editProduct').modal('show');
                        }
                    }
                });
            } else {
                toastr.error('Please select a sales agent.');
            }
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
