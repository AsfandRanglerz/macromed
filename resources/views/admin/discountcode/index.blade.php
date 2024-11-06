@extends('admin.layout.app')
@section('title', 'Discounts Code')
@section('content')
    <style>
        .blinking-text {
            color: red;
            font-weight: bold;
            animation: blink-animation 1s infinite;
        }

        @keyframes blink-animation {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
    {{--  Create Discounts --}}
    <div class="modal fade" id="createDiscountsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Discounts Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createDiscountsForm" enctype="multipart/form-data">
                        <div class="row col-12 col-md-12">
                            <div class="form-group col-md-6">
                                <label for="discount_percentage">Discount Percentage:</label>
                                <input type="number" name="discount_percentage" class="form-control" min="0"
                                    max="100" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date:</label>
                                <input type="datetime-local" name="start_date" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="end_date">End Date:</label>
                                <input type="datetime-local" name="end_date" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="min_quantity">Min Quantity:</label>
                                <input type="number" name="min_quantity" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="max_quantity">Max Quantity:</label>
                                <input type="number" name="max_quantity" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="usage_limit">Usage Limit:</label>
                                <input type="number" name="usage_limit" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
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
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Category Discount </h5>
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
                        <div class="row col-12 col-md-12">
                            <div class="form-group col-md-6">
                                <label for="discount_percentage">Discount Percentage:</label>
                                <input type="number" name="discount_percentage" class="form-control discount_percentage"
                                    min="0" max="100" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date:</label>
                                <input type="datetime-local" name="start_date" class="form-control start_date">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="end_date">End Date:</label>
                                <input type="datetime-local" name="end_date" class="form-control end_date">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="min_quantity">Min Quantity:</label>
                                <input type="number" name="min_quantity" class="form-control min_quantity">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="max_quantity">Max Quantity:</label>
                                <input type="number" name="max_quantity" class="form-control max_quantity">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="usage_limit">Usage Limit:</label>
                                <input type="number" name="usage_limit" class="form-control usage_limit">
                            </div>
                      >
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center mb-0">
                    <button type="button" class="btn btn-primary" onclick="updateDiscounts()">Update</button>
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
                        {{-- <a class="btn btn-primary mb-3" href="{{ route('category.index') }}">Back</a> --}}

                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Discounts Code</h4>
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
                                            <th>Code</th>
                                            <th> Percentage </th>
                                            <th>Expiration</th>
                                            <th>Total Usage Limit</th>
                                            <th>Remaining Usage Limit</th>
                                            <th>Expiration Status</th>
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
    <script>
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('discountsCode.get') }}",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1; // Display row index
                        }
                    },
                    {
                        "data": "discount_code"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return row.discount_percentage + "%";
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return renderTimeLeft(row); // Call the render function
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return row.usage_limit;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return row.remaining_usage_limit;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            if (row.expiration_status == 'active') {
                                return '<span class="text-success">Active</span>'
                            } else {
                                return '<span class="text-danger">Expired</span>'
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.status == '1' ? 'btn-success' : 'btn-danger';
                            var buttonText = row.status == '1' ? 'Active' : 'Inactive';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row.id + '">' + buttonText + '</button>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-0 mr-1 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-0 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });

            function renderTimeLeft(row) {
                var endDate = new Date(row.end_date);
                var currentDate = new Date();
                var timeDiff = endDate - currentDate;

                if (timeDiff <= 0) {
                    return '<span class="blinking-text">Expired</span>'; // Display 'Expired' if the discount is no longer valid
                } else {
                    // Calculate days, hours, and minutes left
                    var daysLeft = Math.floor(timeDiff / (1000 * 3600 * 24));
                    var hoursLeft = Math.floor((timeDiff % (1000 * 3600 * 24)) / (1000 * 3600));
                    var minutesLeft = Math.floor((timeDiff % (1000 * 3600)) / (1000 * 60));

                    return `<span class="blinking-text">${row.discount_percentage}% off - ${daysLeft} days ${hoursLeft} hours ${minutesLeft} minutes left</span>`;
                }
            }
            setInterval(function() {
                dataTable.rows().every(function() {
                    var rowData = this.data();
                    var updatedTimeLeft = renderTimeLeft(rowData); // Update the time left
                    this.cell(':eq(3)').data(
                        updatedTimeLeft);
                });
            }, 60000)
            // Event listeners for edit and delete buttons
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
                url: '{{ route('discountsCode.create') }}',
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
        function formatToLocalDateTime(dateString) {
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16); // Remove seconds and timezone
        }

        function editDiscountsModal(id) {
            var showDiscounts = '{{ route('discountsCode.show', ':id') }}';
            $.ajax({
                url: showDiscounts.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log("data", response);
                    $('#editDiscounts .name').val(response.name);
                    $('#editDiscounts .discount_percentage').val(response.discount_percentage);
                    $('#editDiscountsModal .start_date').val(formatToLocalDateTime(response.start_date));
                    $('#editDiscountsModal .end_date').val(formatToLocalDateTime(response.end_date));
                    $('#editDiscounts .min_quantity').val(response.min_quantity);
                    $('#editDiscounts .max_quantity').val(response.max_quantity);
                    $('#editDiscounts .usage_limit').val(response.usage_limit);
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
            var updateDiscounts = '{{ route('discountsCode.update', ':id') }}';
            var id = $('#editDiscountsModal').data('id');
            var formData = new FormData($('#editDiscounts')[0]);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
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
                    console.log("data", xhr);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while updating the discountsCode.');
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
                url: "{{ route('discountsCode.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                    subadminId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Discount Deleted Successfully!');
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
