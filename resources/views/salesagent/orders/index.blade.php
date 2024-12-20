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
                            return 'Rs ' + data;
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

        });
    </script>

@endsection
