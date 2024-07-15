@extends('admin.layout.app')
@section('title', 'Sales Managers')
@section('content')
    {{-- Create SalesAgent Model  --}}
    <div class="modal fade" id="createSalesAgentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Sales Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSalesAgentForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Active Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">User Type</label>
                                    <select name="user_type" class="form-control" id="user_type">
                                        <option value="">Select User Type</option>
                                        <option value="SalesAgent">Sub Admins</option>
                                        <option value="customer">Customer</option>
                                        <option value="salesmanager">Sales Manager</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <h4>Add Accounts Information:</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_name">Account Name</label>
                                    <input type="text" class="form-control" name="account_name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name</label>
                                    <input type="text" class="form-control" name="account_holder_name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" class="form-control" name="account_number">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="submitSalesAgentForm()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit SalesAgent Modal -->
    <div class="modal fade" id="editSalesAgentModal" tabindex="-1" role="dialog"
        aria-labelledby="editSalesAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSalesAgentModalLabel">Edit Sales Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSalesAgentForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Active Status</label>
                                    <select name="status" class="form-control status">
                                        <option value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">User Type</label>
                                    <select name="user_type" class="form-control user_type">
                                        <option value="">Select User Type</option>
                                        <option value="SalesAgent">Sales Manager</option>
                                        <option value="customer">Customer</option>
                                        <option value="salesmanager">Sales Manager</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control image" name="image">
                                    <img id="imagePreview" src="" alt="Image Preview"
                                        style="display: none; max-width: 100px; margin-top: 10px;">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                            <h4>Update Accounts Information:</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_name">Account Name</label>
                                        <input type="text" class="form-control account_name" name="account_name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_holder_name">Account Holder Name</label>
                                        <input type="text" class="form-control account_holder_name"
                                            name="account_holder_name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_number">Account Number</label>
                                        <input type="text" class="form-control account_number" name="account_number">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="submitEditSalesAgentForm()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete SalesAgent Modal -->
    <div class="modal fade" id="deleteSalesAgentModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSalesAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSalesAgentModalLabel">Delete Sales Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this SalesAgent?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSalesAgent">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Reason Modal --}}
    <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">Reason for Deactivation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reasonForm">
                        <div class="form-group">
                            <textarea class="form-control" id="reason" rows="3" name="reason" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="submitReason">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Sales Managers</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createSalesAgentModal">
                                    Create Sales Managers
                                </a>
                                <table class="responsive table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
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
{{-- ############ Data Table ############## --}}
@section('js')
    <script>
        function reloadDataTable() {
            var dataTable = $('.table').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('.table').DataTable({
                "ajax": {
                    "url": "{{ route('salesagent.get') }}",
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
                        "data": "name"
                    },
                    {
                        "data": "email"
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
                            return '<a href="' +
                                "{{ route('salesagent.profile', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 mr-2 text-white"><i class="fas fa-eye"></i></a>' +
                                '<button class="btn btn-success mb-0 mr-2 text-white editSalesAgentBtn btn-sm" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-0 mr-2 text-white deleteSalesAgentBtn btn-sm" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });


            $('.table').on('click', '.editSalesAgentBtn', function() {
                var salesAgentId = $(this).data('id');
                openEditSalesAgentModal(salesAgentId);
            });
            $('.table').on('click', '.deleteSalesAgentBtn', function() {
                var salesAgentId = $(this).data('id');
                deleteSalesAgentModal(salesAgentId);
            });
        });

        // ##############Create Sales Manager################
        $(document).ready(function() {
            $('#createSalesAgentForm input, #createSalesAgentForm select, #createSalesAgentForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function submitSalesAgentForm() {
            var formData = new FormData($('#createSalesAgentForm')[0]);
            var createButton = $('#createSalesAgentModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('salesagent.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Sales Manager Created Successfully!');
                    $('#createSalesAgentModal').modal('hide');
                    reloadDataTable();
                    $('#createSalesAgentForm').reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid').siblings(
                                '.invalid-feedback').text(value);

                        });
                    } else {
                        console.log("Error:", xhr);
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        // Remove validation messages when user starts typing
        $('#createSalesAgentForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        // ######Get & Update Sales Manager#########

        function openEditSalesAgentModal(salesAgentId) {
            var salesAgentShowRoute = '{{ route('salesagent.show', ':id') }}';
            $.ajax({
                url: salesAgentShowRoute.replace(':id', salesAgentId),
                type: 'GET',
                success: function(response) {
                    $('#editSalesAgentForm .name').val(response.name);
                    $('#editSalesAgentForm .email').val(response.email);
                    $('#editSalesAgentForm .phone').val(response.phone);
                    $('#editSalesAgentForm .status').val(response.status);
                    $('#editSalesAgentForm .user_type').val(response.user_type);
                    // Assuming response.image contains the URL of the existing image
                    var imageUrl = response.image;
                    var baseUrl = 'http://localhost/macromed/';
                    var responseImage = baseUrl + response.image;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }
                    $('#editSalesAgentForm .account_number').val(response.bank_accounts.account_number);
                    $('#editSalesAgentForm .account_name').val(response.bank_accounts.account_name);
                    $('#editSalesAgentForm .account_holder_name').val(response.bank_accounts
                    .account_holder_name);
                    $('#editSalesAgentModal').modal('show');
                    $('#editSalesAgentModal').data('salesAgentId', salesAgentId);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update SalesAgent#############
        $(document).ready(function() {
            $('#editSalesAgentForm input, #editSalesAgentForm select, #editSalesAgentForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function submitEditSalesAgentForm() {
            var salesAgentUpdateRoute = '{{ route('salesagent.update', ':id') }}';
            var salesAgentId = $('#editSalesAgentModal').data('salesAgentId');
            var formData = new FormData($('#editSalesAgentForm')[0]);
            $.ajax({
                url: salesAgentUpdateRoute.replace(':id', salesAgentId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Sales Manager Updated Successfully!');
                    $('#editSalesAgentModal').modal('hide');
                    reloadDataTable();
                    $('#editSalesAgentForm').reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid').siblings(
                                '.invalid-feedback').text(value);

                        });
                    } else {
                        console.log("Error:", xhr);
                    }
                }
            });
        }
        // ############# Delete SalesAgent Data###########
        function deleteSalesAgentModal(salesAgentId) {
            $('#confirmDeleteSalesAgent').data('SalesAgent-id', salesAgentId);
            $('#deleteSalesAgentModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSalesAgent').click(function() {
                var salesAgentId = $(this).data('SalesAgent-id');
                deleteSalesAgent(salesAgentId);
            });
        });

        function deleteSalesAgent(salesAgentId) {
            $.ajax({
                url: "{{ route('salesagent.delete', ['id' => ':salesAgentId']) }}".replace(':salesAgentId',
                    salesAgentId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Sales Manager Deleted Successfully!');
                    $('#deleteSalesAgentModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        // ################ Active and Inactive code ############
        $(document).ready(function() {
            var userId;
            var button;

            $('.table').on('click', '#update-status', function() {
                button = $(this);
                userId = button.data('userid');
                var currentStatus = button.text().trim().toLowerCase();
                var newStatus = currentStatus === 'active' ? '0' : '1'; // Toggle the status

                if (newStatus === '0') {
                    $('#reasonModal').modal('show');
                } else {
                    updateUserStatus(userId, newStatus, ""); // Directly update status without modal
                }
            });

            $('#submitReason').on('click', function() {
                var reason = $('#reason').val().trim();
                if (reason === "") {
                    alert("Please provide a reason for deactivation.");
                    return;
                }
                $('#reasonModal').modal('hide');
                updateUserStatus(userId, '0', reason);
            });

            function updateUserStatus(userId, newStatus, reason) {
                button.prop('disabled', true);

                $.ajax({
                    url: '{{ route('agentBlock.update', ['id' => ':userId']) }}'.replace(':userId',
                        userId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus,
                        reason: reason
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Update button text and class
                        var buttonText = newStatus === '1' ? 'Active' : 'In Active';
                        var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                        button.text(buttonText).removeClass('btn-success btn-danger').addClass(
                            buttonClass);
                        // Update status cell content
                        var statusCell = button.closest('tr').find('td:eq(6)');
                        var statusText = newStatus === '1' ? 'Active' : 'In Active';
                        var statusClass = newStatus === '1' ? 'text-success' : 'text-danger';
                        statusCell.html('<span class="' + statusClass + '">' + statusText + '</span>');
                        reloadDataTable();
                    },
                    error: function(xhr, status, error) {
                        toastr.success(response.error);
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        // Enable the button again
                        button.prop('disabled', false);
                    }
                });
            }
        });
    </script>
@endsection
