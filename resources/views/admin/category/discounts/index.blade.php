@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    {{--  Create Discounts --}}
    <div class="modal fade" id="createDiscountsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Discounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createDiscountsForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="discount_percentage">Discount Name:</label>
                            <input type="text" name="name" class="form-control" min="0" max="100"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="discount_percentage">Discount Percentage:</label>
                            <input type="number" name="discount_percentage" class="form-control" min="0"
                                max="100" required>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="form-group ">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="createDiscounts()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Subadmin Modal -->
    <div class="modal fade" id="deleteSubadminModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSubadminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Product Variant </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Discount ?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Discounts Modal -->
    <div class="modal fade" id="editDiscountsModal" tabindex="-1" role="dialog" aria-labelledby="editDiscountsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDiscountsModalLabel">Edit Discounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editDiscounts" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="discount_percentage">Discount Percentage:</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control"
                                min="0" max="100" required>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center mb-0">
                    <button type="button" class="btn btn-success" onclick="updateDiscounts()">Update</button>
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
                        <a class="btn btn-primary mb-3" href="{{ route('category.index') }}">Back</a>
                        @if ($categories->status == '1')
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-12">
                                        <h4>Discounts</h4>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                        data-target="#createDiscountsModal">
                                        Create
                                    </a>
                                    <table class="responsive table table-striped table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Discount Percentage </th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="inactive-product">
                                <h4 class="alert-heading text-danger">Warning!</h4>
                                <p class="text-danger">This product is not active. You cannot view product Discounts to it.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('js')
    <script>
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {

            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('discounts.get', ['id' => $id]) }}",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    },
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1; // Display row index
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "discount_percentage"
                    },
                    {
                        "data": "start_date"
                    },
                    {
                        "data": "end_date"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.status == '1' ? 'btn-success' : 'btn-danger';
                            var buttonText = row.status == '1' ? 'Active' : 'In Active';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-1 mr-1 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-0 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';

                        }
                    }
                    // { "data": "description" }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editDiscountsModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });
        });

        function createDiscounts() {
            var formData = new FormData($('#createDiscountsForm')[0]);
            var createButton = $('#createDiscountsModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('discounts.create', ['id' => $id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Discount Created Successfully!');
                    $('#createDiscountsModal').modal('hide');
                    reloadDataTable();
                    $('#createDiscountsModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while updating the product.');
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        $('#createDiscountsForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        $('#createDiscountsModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        $('#createDiscountsModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        // ######Get & Update Discounts#########

        function editDiscountsModal(id) {
            var showDiscounts = '{{ route('discounts.show', ':id') }}';
            $.ajax({
                url: showDiscounts.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editDiscounts .tooltip_information').val(response.tooltip_information);
                    $('#editDiscounts .m_p_n').val(response.m_p_n);
                    $('#editDiscounts .s_k_u').val(response.s_k_u);
                    $('#editDiscounts .packing').val(response.packing);
                    $('#editDiscounts .unit').val(response.unit);
                    $('#editDiscounts .quantity').val(response.quantity);
                    $('#editDiscounts .price_per_unit').val(response.price_per_unit);
                    $('#editDiscounts .selling_price_per_unit').val(response.selling_price_per_unit);
                    $('#editDiscounts .actual_weight').val(response.actual_weight);
                    $('#editDiscounts .shipping_weight').val(response.shipping_weight);
                    $('#editDiscounts .remaining_quantity').val(response.remaining_quantity);
                    $('#editDiscounts .shipping_chargeable_weight').val(response
                        .shipping_chargeable_weight);
                    $('#editDiscounts .status').val(response.status);
                    if (response.description !== null) {
                        geteditor.setData(response.description);
                    } else {
                        geteditor.setData(''); // Or set it to an empty string
                    }
                    $('#editDiscountsModal').modal('show');
                    $('#editDiscountsModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editDiscounts input, #editDiscounts select, #editDiscounts textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateDiscounts() {
            var updateDiscounts = '{{ route('discounts.update', ':id') }}';
            var id = $('#editDiscountsModal').data('id');
            var form = document.getElementById("editDiscounts");
            var tooltip_information = form["tooltip_information"].value;
            var m_p_n = form["m_p_n"].value;
            var s_k_u = form["s_k_u"].value;
            var packing = form["packing"].value;
            var unit = form["unit"].value;
            var quantity = form["quantity"].value;
            var price_per_unit = form["price_per_unit"].value;
            var selling_price_per_unit = form["selling_price_per_unit"].value;
            var actual_weight = form["actual_weight"].value;
            var shipping_weight = form["shipping_weight"].value;
            var shipping_chargeable_weight = form["shipping_chargeable_weight"].value;
            var status = form["status"].value;
            var description = geteditor.getData();
            // ########### Form Data ###########
            var formData = new FormData();
            formData.append('tooltip_information', tooltip_information);
            formData.append('m_p_n', m_p_n);
            formData.append('s_k_u', s_k_u);
            formData.append('packing', packing);
            formData.append('unit', unit);
            formData.append('quantity', quantity);
            formData.append('price_per_unit', price_per_unit);
            formData.append('selling_price_per_unit', selling_price_per_unit);
            formData.append('actual_weight', actual_weight);
            formData.append('shipping_chargeable_weight', shipping_chargeable_weight);
            formData.append('shipping_weight', shipping_weight);
            formData.append('status', status);
            formData.append('description', description);

            $.ajax({
                url: updateDiscounts.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Discounts Updated Successfully!');
                    $('#editDiscountsModal').modal('hide');
                    reloadDataTable();
                    $('#editDiscountsModal form')[0].reset();

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while updating the product.');
                    }
                }
            });
        }
        // Update status event listener
        $('#example').on('click', '#update-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentStatus = button.text().trim().toLowerCase();
            var newStatus = currentStatus === 'active' ? '1' : '0';
            button.prop('disabled', true);
            $.ajax({
                url: '{{ route('discountsBlock.update', ['id' => ':userId']) }}'.replace(
                    ':userId', userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus === '1' ? 'Active' : 'Inactive';
                    var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(
                        buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
                    statusCell.html('<span class="' + statusClass + '">' + statusText +
                        '</span>');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    // Enable the button again
                    button.prop('disabled', false);
                }
            });
        });

        // Delete Subadmin function
        function deleteSubadminModal(subadminId) {
            $('#confirmDeleteSubadmin').data('subadmin-id', subadminId);
            $('#deleteSubadminModal').modal('show');
        }

        $('#confirmDeleteSubadmin').click(function() {
            var subadminId = $(this).data('subadmin-id');
            deleteSubadmin(subadminId);
        });

        function deleteSubadmin(subadminId) {
            $.ajax({
                url: "{{ route('discounts.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                    subadminId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Product Variant Deleted Successfully!');
                    $('#deleteSubadminModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>

@endsection
