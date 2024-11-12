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

                                {{-- <div class="form-group col-sm-3 mb-2">
                                    <label for="areaSelect">Select Area</label>
                                    <select id="areaSelect" class="form-control select2">
                                        <option value="">Select Area</option>
                                        <!-- Populate this dynamically with areas -->
                                    </select>
                                </div> --}}

                                <div class="form-group col-sm-3 mb-2">
                                    <label for="supplierSelect">Select Supplier</label>
                                    <select id="supplierSelect" class="form-control select2">
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="form-group col-sm-4 mb-2">
                                    <label for="productSelect">Select Product</label>
                                    <select id="productSelect" class="form-control select2">
                                        <option value="">Select Product</option>
                                        <!-- Populate this dynamically with products -->
                                    </select>
                                </div> --}}

                                {{-- <div class="form-group col-sm-3 mb-2 d-flex align-items-end">
                                    <button class="btn btn-primary" onclick="loadData()">Apply Filters</button>
                                </div> --}}
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
                                            <th>City</th>
                                            <th>Sales Agent Commission</th>
                                            <th>Total Profit</th>
                                            <th>Total Amount</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Details</th>
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
                    "url": "{{ route('admin.reports.data') }}",
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
                        $('#totalAmount').text('Total Amount: $ ' + (json.totalAmount || 0).toFixed(
                            2));
                        return json.salesData;
                    }
                },
                "dom": 'Bfrtip',
                "buttons": ['excel', 'print'],
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
                            var totalProfit = 0;
                            data.order_item.forEach(function(item) {
                                // Use discounted price if available, otherwise use selling price
                                var sellingPrice = item.product_variant
                                    .discounted_price_per_unit > 0 ?
                                    item.product_variant.discounted_price_per_unit :
                                    item.product_variant.selling_price_per_unit;

                                var profit = (sellingPrice - item.product_variant
                                    .price_per_unit) * item.quantity;
                                totalProfit += profit;
                            });
                            return '$' + totalProfit.toFixed(2);
                        }
                    },
                    {
                        "data": "total",
                        "render": (data) => '$' + parseFloat(data).toFixed(2)
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
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('reportsinvoice.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary text-white"><i class="fas fa-user"></i></a>';
                        },
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
