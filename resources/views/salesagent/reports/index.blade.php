@extends('salesagent.layout.app')
@section('title', 'Reports')
@section('content')
    <style>
        .active-button {
            background-color: var(--theme-color-dark) !important;
            color: #fff !important;
        }
    </style>

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header justify-content-center">
                                <h4>Reports</h4>
                            </div>
                            <div class="row col-12 mt-3 d-flex justify-content-center">
                                <div class="form-group col-sm-12 mb-2 d-flex align-items-start">
                                    <button class="btn btn-danger" id="filterButton" onclick="clearFilters()">Clear
                                        Filters</button>
                                </div>
                                <div class="form-group col-sm-3 mb-2">
                                    <label for="periodSelect">Select Period</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="daily" selected>Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3 mb-2">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                                </div>
                                <div class="form-group col-sm-3 mb-2">
                                    <label for="endDate">End Date</label>
                                    <input type="date" id="endDate" class="form-control" placeholder="End Date">
                                </div>


                            </div>

                            <div class="card-body table-responsive">
                                <h4 id="totalAmount" class="mb-2"></h4>
                                <table id="example" class="responsive table table-striped table-bordered">
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
    <script>
        function clearFilters() {
            document.getElementById('periodSelect').value = 'daily';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            $('#areaSelect').val('').trigger('change');
            $('#supplierSelect').val(null).trigger('change');
            $('#productSelect').val('').trigger('change');
            loadData();
        }

        $(document).ready(function() {
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('user.reports.data') }}",
                    "type": "GET",
                    "data": function(d) {
                        d.period = $('#periodSelect').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.area = $('#areaSelect').val();
                        d.supplier = $('#supplierSelect').val();
                        d.product = $('#productSelect').val();
                    },
                    "dataSrc": function(json) {
                        $('#totalAmount').text('Total Amount: Rs ' + (json.totalAmount || 0).toFixed(
                            2));
                        return json.salesData;
                    }
                },
                "dom": 'Bfrtip',
                "buttons": ['excel', 'print'],
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

            window.loadData = function() {
                dataTable.ajax.reload();
            };

            // Modify change event to handle date filters
            $('#startDate, #endDate').change(function() {
                if ($('#startDate').val() || $('#endDate').val()) {
                    $('#periodSelect').val(''); // Clear period selection
                } else {
                    $('#periodSelect').val('daily'); // Reset to default if no dates
                }
                loadData();
            });

            $('#periodSelect, #areaSelect, #supplierSelect, #productSelect').change(function() {
                loadData();
            });
        });
    </script>




@endsection
