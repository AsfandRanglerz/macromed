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
                                {{-- <div class="form-group col-sm-3 mb-2">
                                    <label for="periodSelect">Select Period</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="daily" selected>Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div> --}}

                                <div class="form-group col-sm-4 mb-2">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                                </div>
                                <div class="form-group col-sm-4 mb-2">
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

                                <div class="form-group col-sm-4 mb-2">
                                    <label for="supplierSelect">Select Supplier</label>
                                    <select id="supplierSelect" class="form-control select2">
                                        <option value="" selected disabled>Select Supplier</option>
                                        <option value="">Un-Select Supplier</option>
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
                            <div class="row col-12 mt-3 d-flex justify-content-center">
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="citySelect">Select City</label>
                                    <select id="citySelect" class="form-control select2">
                                        <option value="" selected disabled>Select City</option>
                                        <option value="">Un-Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->name }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="categorySelect">Select Product Category</label>
                                    <select id="categorySelect" class="form-control select2">
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="">Un-Select Category</option>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="productSelect">Select Product</label>
                                    <select id="productSelect" class="form-control select2">
                                        <option value="" selected disabled>Select Product</option>
                                        <option value="">Un-Select Product</option>

                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->short_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row col-12 mt-3 d-flex">
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="managerSelect">Select Manager</label>
                                    <select id="managerSelect" class="form-control select2">
                                        <option value="" selected disabled>Select Manager</option>
                                        <option value="">Un-Select Manager</option>
                                        @foreach ($managers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                            <th>Total Amount</th>
                                            <th>Order Date</th>
                                            <th>Sales Agent Commission</th>
                                            <th>Total Profit</th>
                                            <th>Discounted Total</th>
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
        // $(document).ready(function () {
        //     $('#categorySelect').on('change', function () {
        //         const categoryId = $(this).val(); // Get selected category ID
        //         const productSelect = $('#productSelect'); // Target the product dropdown

        //         // Clear previous product options
        //         productSelect.html('<option value="" selected disabled>Loading...</option>');

        //         if (categoryId) {
        //             // Make an AJAX request to fetch products
        //             $.ajax({
        //                 url: "{{ route('admin.products.data') }}", // Backend route
        //                 type: "GET",
        //                 data: { cat_with: categoryId }, // Send category ID to server
        //                 success: function (response) {
        //                     // Clear existing options
        //                     productSelect.html('<option value="" selected disabled>Select Product</option>');

        //                     // Iterate over the response data
        //                     $.each(response, function (index, item) {
        //                         // Check if `products` field is not null
        //                         if (item.products !== null) {
        //                             productSelect.append(
        //                                 `<option value="${item.products.id}">${item.products.short_name}</option>`
        //                             );
        //                         }
        //                     });

        //                     // Show a message if no products are found
        //                     if (productSelect.children().length === 1) {
        //                         productSelect.html('<option value="" selected disabled>No Products Found</option>');
        //                     }
        //                 },
        //                 error: function (xhr, status, error) {
        //                     console.error('Error fetching products:', error);
        //                     alert('Failed to fetch products. Please try again.');
        //                 }
        //             });
        //         } else {
        //             // If no category is selected, reset the dropdown
        //             productSelect.html('<option value="" selected disabled>Select Product</option>');
        //         }
        //     });
        // });
    </script>

    <script>
        function clearFilters() {
            // document.getElementById('periodSelect').value = 'daily';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            $('#areaSelect').val('').trigger('change');
            $('#supplierSelect').val(null).trigger('change');
            $('#productSelect').val('').trigger('change');
            $('#categorySelect').val('').trigger('change');
            $('#citySelect').val('').trigger('change');
            $('#managerSelect').val('').trigger('change');
            loadData();
        }

        // $(document).ready(function() {
        //     var dataTable = $('#example').DataTable({
        //         "ajax": {
        //             "url": "{{ route('admin.reports.data') }}",
        //             "type": "GET",
        //             "data": function(d) {
        //                 d.period = $('#periodSelect').val();
        //                 d.startDate = $('#startDate').val();
        //                 d.endDate = $('#endDate').val();
        //                 d.area = $('#areaSelect').val();
        //                 d.supplier = $('#supplierSelect').val();
        //                 d.product = $('#productSelect').val();
        //                 d.category = $('#categorySelect').val();
        //                 d.city = $('#citySelect').val();
        //             },
        //            "dataSrc": function(json) {
        //             // Ensure totalAmount is a number or default to 0
        //             const totalAmount = parseFloat(json.totalAmount) || 0;

        //             // Update the UI with formatted total amount
        //             $('#totalAmount').text('Total Amount: Rs ' + totalAmount.toFixed(2));

        //             // Return sales data for the DataTable
        //             return json.salesData;
        //         }
        //         },
        //         "dom": 'Bfrtip',
        //         "buttons": ['excel', 'print'],
        //         "columns": [{
        //                 "data": null,
        //                 "render": (data, type, row, meta) => meta.row + 1
        //             },
        //             {
        //                 "data": "order_id"
        //             },
        //             {
        //                 "data": null,
        //                 "render": (data) => data.users.name
        //             },
        //             {
        //                 "data": null,
        //                 "render": (data) => data.sales_agent.name
        //             },
        //             {
        //                 "data": "country"
        //             },
        //             {
        //                 "data": "state"
        //             },
        //             {
        //                 "data": "city"
        //             },
        //             {
        //                 "data": "product_commission",
        //                 "render": (data) => 'Rs ' + data
        //             },
        //             {
        //                 "data": null,
        //                 "render": function(data) {
        //                     var totalProfit = 0;
        //                     data.order_item.forEach(function(item) {
        //                         var profit = (item.product_variant.selling_price_per_unit -
        //                                 item.product_variant.price_per_unit) * item
        //                             .quantity;
        //                         totalProfit += profit;
        //                     });
        //                     return 'Rs ' + totalProfit.toFixed(2);
        //                 }
        //             },
        //             {
        //                 "data": "total",
        //                 "render": (data) => 'Rs ' + parseFloat(data).toFixed(2)
        //             },
        //             {
        //                 "data": "discounted_total",
        //                 "render": function(data, type, row) {
        //                     if (data) {
        //                         return 'Rs ' + data;
        //                     } else {
        //                         return '<span class="text-danger">No Discount Total Found!</span>'
        //                     }

        //                 }
        //             },
        //             {
        //                 "data": "created_at",
        //                 "render": (data) => {
        //                     const createdAtDate = new Date(data);
        //                     return `<div>${createdAtDate.toLocaleDateString()}</div><div>${createdAtDate.toLocaleTimeString()}</div>`;
        //                 }
        //             },
        //             {
        //                 "data": "status",
        //                 "render": function(data) {
        //                     return data === "inProcess" ?
        //                         '<span style="color: red;">Pending</span>' :
        //                         data === "completed" ?
        //                         '<span style="color: green;">Delivered</span>' :
        //                         data;
        //                 }
        //             },
        //             {
        //                 "render": function(data, type, row) {
        //                     return '<a href="' +
        //                         "{{ route('reportsinvoice.index', ['id' => ':id']) }}"
        //                         .replace(':id', row.id) +
        //                         '" class="btn btn-primary text-white"><i class="fas fa-user"></i></a>';
        //                 },
        //             },
        //         ]
        //     });

        //     window.loadData = function() {
        //         dataTable.ajax.reload();
        //     };

        //     // Modify change event to handle date filters
        //     // $('#startDate, #endDate').change(function() {
        //     //     if ($('#startDate').val() || $('#endDate').val()) {
        //     //         $('#periodSelect').val(''); // Clear period selection
        //     //     } else {
        //     //         $('#periodSelect').val('daily'); // Reset to default if no dates
        //     //     }
        //     //     loadData();
        //     // });

        //     $('#periodSelect, #areaSelect, #supplierSelect, #productSelect, #categorySelect ,#citySelect').change(function() {
        //         loadData();
        //     });
        // });




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
                        d.category = $('#categorySelect').val();
                        d.city = $('#citySelect').val();
                        d.manager = $('#managerSelect').val();
                    },
                    "dataSrc": function(json) {
                        const totalAmount = parseFloat(json.totalAmount) || 0;
                        $('#totalAmount').text('Total Amount: Rs ' + totalAmount.toFixed(2));
                        return json.salesData;
                    }
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'excel',
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Sales Report', // Title for the print view
                        customize: function(win) {
                            $(win.document.body).prepend(`
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <img src="{{ asset('public/admin/assets/images/logo-macromed.png') }}" alt="Logo" style="height: 100px">
                                    <h3 class="mt-3 mb-2">Macromed PVT.LTD.</h3>
                                    <p class="mb-0"><b>Contact:</b> +92 (310) 760 8641</p>
                                    <p class="mb-0"><b>Email:</b> info@macromed.com.pk</p>
                                    <p class="mb-0"><b>Address:</b> FF 130, Defence Shopping Mall, DHA Main Boulevard, Lahore Cantt,. Lahore-Pakistan</p>
                                </div>
                            `);

                            // Optional: Add custom styles for better formatting
                            $(win.document.head).append(`
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                margin: 20px;
                            }
                            h3, p {
                                margin: 5px 0;
                            }
                            table {
                                margin-top: 20px;
                                border-collapse: collapse;
                                width: 100%;
                            }
                            table, th, td {
                                border: 1px solid #ddd;
                            }
                        </style>
                    `);
                        }
                    }
                ],
                "columns": [{
                        "data": null,
                        "render": (data, type, row, meta) => meta.row + 1
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('reportsinvoice.index', ['id' => ':id']) }}".replace(':id', row.id) +
                                '?order_id=' + row.order_id + '">' + row.order_id + '</a>';
                        },
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
                        "data": "discounted_total",
                        "render": function(data, type, row) {
                            if (data) {
                                return 'Rs ' + data;
                            } else {
                                return '<span class="text-danger">No Discount Total Found!</span>'
                            }
                        }
                    },
                    {
                        "data": "created_at",
                        "render": (data) => {
                            const createdAtDate = new Date(data);
                            return `<div>${createdAtDate.toLocaleDateString()}</div><div>${createdAtDate.toLocaleTimeString()}</div>`;
                        }
                    },
                    {
                        "data": "product_commission",
                        "render": (data) => 'Rs ' + data
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            var totalProfit = 0;
                            data.order_item.forEach(function(item) {
                                var profit = (item.product_variant.selling_price_per_unit -
                                    item.product_variant.price_per_unit) * item.quantity;
                                totalProfit += profit;
                            });
                            return 'Rs ' + totalProfit.toFixed(2);
                        }
                    },
                    {
                        "data": "total",
                        "render": (data) => 'Rs ' + parseFloat(data).toFixed(2)
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
                                "{{ route('reportsinvoice.index', ['id' => ':id']) }}".replace(
                                    ':id', row.id) +
                                '" class="btn btn-primary text-white"><i class="fas fa-user"></i></a>';
                        },
                    }
                ]
            });

            window.loadData = function() {
                dataTable.ajax.reload();
            };

            $('#periodSelect, #areaSelect, #supplierSelect, #productSelect, #categorySelect ,#citySelect ,#managerSelect').change(
                function() {
                    loadData();
                });

                // Listen for changes in startDate and endDate inputs
            $('#startDate, #endDate').on('change', function() {
                    dataTable.ajax.reload(); // Reload DataTable with updated parameters
                });
        });
    </script>




@endsection
