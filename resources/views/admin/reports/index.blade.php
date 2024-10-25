@extends('admin.layout.app')
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
                                <!-- Period Selection Dropdown -->
                                <select id="periodSelect" class="form-control col-sm-2 mr-2 mb-2 mb-md-0"
                                    onchange="loadData()">
                                    <option value="daily" selected>Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>

                                <!-- Date Range for Custom Filtering -->
                                <input type="date" id="startDate" class="form-control col-sm-2 mr-2 mb-2 mb-md-0"
                                    placeholder="Start Date">
                                <input type="date" id="endDate" class="form-control col-sm-2 mr-2 mb-2 mb-md-0"
                                    placeholder="End Date">

                                <!-- Area, Supplier, and Product Dropdowns -->
                                <select id="areaSelect" class="form-control col-sm-2 mr-2 mb-2 mb-md-0">
                                    <option value="">Select Area</option>
                                    <!-- Populate this dynamically with areas -->
                                </select>
                                <select id="supplierSelect" class="form-control col-sm-2 mr-2 mb-2 mb-md-0">
                                    <option value="">Select Supplier</option>
                                    <!-- Populate this dynamically with suppliers -->
                                </select>
                                <select id="productSelect" class="mt-3 form-control col-sm-2 mb-2 mb-md-0">
                                    <option value="">Select Product</option>
                                    <!-- Populate this dynamically with products -->
                                </select>
                                <button class="mt-3 btn btn-primary ml-2" onclick="loadData()">Apply Filters</button>
                            </div>

                            <div class="card-body table-responsive">
                                <h4 id="totalAmount" class="mb-2"></h4>
                                <table id="example" class="responsive table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Order Code</th>
                                            <th>User Name</th>
                                            <th>Agent Name</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>Size</th>
                                            <th>Sales Agent Commission</th>
                                            <th>Total Profit</th>
                                            <th>Total Amount</th>
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
        $(document).ready(function() {
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('admin.reports.data', ['period' => 'daily']) }}",
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
                    },
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'excel', 'print'
                ],
                "columns": [{
                        "data": null,
                        "render": (data, type, row, meta) => meta.row + 1
                    },
                    {
                        "data": "order_id"
                    },
                    {
                        "data": null,
                        "render": (data) => data.users.name
                    },
                    {
                        "data": null,
                        "render": (data) => data.sales_agent.name
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
                        "render": (data) => '$' + data
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            let totalProfit = 0;
                            data.items.forEach(item => {
                                totalProfit += (item.selling_price_per_unit - item
                                    .price_per_unit) * item.quantity;
                            });
                            return '$: ' + totalProfit.toFixed(2);
                        }
                    },
                    {
                        "data": "total",
                        "render": (data) => '$: ' + parseFloat(data).toFixed(2)
                    },
                    {
                        "data": "created_at",
                        "render": (data) => {
                            const createdAtDate = new Date(data);
                            return `<div>${createdAtDate.toLocaleDateString()}</div><div>${createdAtDate.toLocaleTimeString()}</div>`;
                        }
                    },
                    {
                        "data": "status",
                        "render": function(data) {
                            return data === "inProcess" ?
                                '<span style="color: red;">Pending</span>' :
                                data === "completed" ?
                                '<span style="color: green;">Delivered</span>' :
                                data;
                        }
                    }
                ]
            });

            // window.loadData = function() {
            //     var period = $('#periodSelect').val(); // Get the selected period
            //     if (period) { // Check if period is not empty
            //         dataTable.ajax.url("{{ route('admin.reports.data', ['period' => '']) }}/" + period).load();
            //     } else {
            //         console.error("Period is not defined.");
            //     }
            // };
            // $('#periodSelect').change(function() {
            //     loadData(); // Load data when the period changes
            // });
            // Load default data on page load
            loadData();
        });
    </script>
@endsection
