@extends('admin.layout.app')
@section('title', 'Sub Admins')
@section('content')
    {{-- Create SubAdmin Model  --}}
    <div class="modal fade" id="createSubadminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Sub Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSubadminForm" enctype="multipart/form-data">
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
                                        <option value="subadmin">Sub Admin</option>
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
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="submitSubadminForm()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Subadmin Modal -->
    <div class="modal fade" id="editSubadminModal" tabindex="-1" role="dialog" aria-labelledby="editSubadminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubadminModalLabel">Edit Sub Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSubadminForm" enctype="multipart/form-data">
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
                                        <option value="subadmin">Sub Admin</option>
                                        <option value="customer">Customer</option>
                                        <option value="salesmanager">Sales Manager</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control image" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div> --}}
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
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="submitEditSubadminForm()">Update</button>
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
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Sub Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this subadmin?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Permssion Modal --}}
    <div class="modal fade" id="updatePermissionSubadminModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="update_user_id">
                        <h6>Select Permissions:</h6>
                        @foreach ($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_role_{{ $permission->id }}"
                                    name="update_permissions[]" value="{{ $permission->id }}">
                                <label class="form-check-label"
                                    for="update_role_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-success btn-sm" onclick="updatePermission()">Update</button>
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
                                    <h4>Sub Admins</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createSubadminModal">
                                    Create Sub Admins
                                </a>
                                <table class="responsive table table-striped table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Permissions</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
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
                    "url": "{{ route('subadmin.get') }}",
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
                            var buttonClass = row.status == 1 ? 'btn-success' : 'btn-danger';
                            var buttonText = row.status == 1 ? 'Active' : 'In Active';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-primary mb-0 text-white updatePermissionBtn btn-sm" data-id="' +
                                row.id + '"><i class="fas fa-user"></i></button>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('subadmin.profile', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 mr-2 text-white"><i class="fas fa-eye"></i></a>' +
                                '<button class="btn btn-success mb-0 mr-2 text-white editSubadminBtn btn-sm" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-0 mr-2 text-white deleteSubadminBtn btn-sm" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });


            $('.table').on('click', '.editSubadminBtn', function() {
                var subadminId = $(this).data('id');
                openEditSubadminModal(subadminId);
            });
            $('.table').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });
            $('.table').on('click', '.updatePermissionBtn', function() {
                var subadminId = $(this).data('id');
                openUpdatePermissionSubadminModal(subadminId);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createSubadminForm input, #createSubadminForm select, #createSubadminForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function submitSubadminForm() {
            var formData = new FormData($('#createSubadminForm')[0]);
            var createButton = $('#createSubadminModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('subadmin.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Sub Admin Created Successfully!');
                    $('#createSubadminModal').modal('hide');
                    reloadDataTable();
                    $('#createSubadminForm').reset();
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
        $('#createSubadminForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        // ######Get & Update Sub Admin#########
        var subadminShowRoute = '{{ route('subadmin.show', ':id') }}';
        var subadminUpdateRoute = '{{ route('subadmin.update', ':id') }}';

        function openEditSubadminModal(subadminId) {
            $.ajax({
                url: subadminShowRoute.replace(':id', subadminId),
                type: 'GET',
                success: function(response) {
                    $('#editSubadminForm .name').val(response.name);
                    $('#editSubadminForm .email').val(response.email);
                    $('#editSubadminForm .phone').val(response.phone);
                    $('#editSubadminForm .status').val(response.status);
                    $('#editSubadminForm .user_type').val(response.user_type);
                    // Assuming response.image contains the URL of the existing image
                    var imageUrl = response.image;
                    var baseUrl = 'http://localhost/macromed/';
                    var responseImage = baseUrl + response.image;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }

                    $('#editSubadminModal').modal('show');
                    $('#editSubadminModal').data('subadminId', subadminId);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editSubadminForm input, #editSubadminForm select, #editSubadminForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function submitEditSubadminForm() {
            var subadminId = $('#editSubadminModal').data('subadminId');
            var formData = new FormData($('#editSubadminForm')[0]);
            $.ajax({
                url: subadminUpdateRoute.replace(':id', subadminId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Sub Admin Updated Successfully!');
                    $('#editSubadminModal').modal('hide');
                    reloadDataTable();
                    $('#editSubadminForm').reset();
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
        // ############# Delete Subadmin Data###########
        function deleteSubadminModal(subadminId) {
            $('#confirmDeleteSubadmin').data('subadmin-id', subadminId);
            $('#deleteSubadminModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var subadminId = $(this).data('subadmin-id');
                deleteSubadmin(subadminId);
            });
        });

        function deleteSubadmin(subadminId) {
            $.ajax({
                url: "{{ route('subadmin.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                    subadminId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Sub Admin Deleted Successfully!');
                    $('#deleteSubadminModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        // ############## Asign Permission ###############
        function openUpdatePermissionSubadminModal(userId) {
            $('#update_user_id').val(userId);
            $.ajax({
                url: '{{ route('get.permissions', ':userId') }}'.replace(':userId',
                    userId),
                method: 'GET',
                success: function(response) {
                    $('input[name="update_permissions[]"]').each(function() {
                        var permissionId = $(this).val();
                        var assigned = response.permissions.some(function(permission) {
                            return permission.id == permissionId;
                        });
                        $(this).prop('checked', assigned);
                    });
                    $('#updatePermissionSubadminModal').modal('show'); // Open the modal
                },
                error: function(xhr, status, error) {
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    console.error(xhr.responseText);
                }
            });
        }

        function updatePermission() {
            var userId = $('#update_user_id').val();
            var permissions = [];
            $('input[name="update_permissions[]"]:checked').each(function() {
                permissions.push($(this).val());
            });
            $.ajax({
                url: '{{ route('update.user.permissions', ':userId') }}'.replace(':userId',
                    userId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    permissions: permissions
                },
                success: function(response) {
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    $('#updatePermissionSubadminModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    console.error(xhr.responseText);

                }
            });
        }
        $('.table').on('click', '#update-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentStatus = button.text().trim().toLowerCase();
            var newStatus = currentStatus === 'Active' ? 1 : 0;
            button.prop('disabled', true);

            $.ajax({
                url: '{{ route('userBlock.update', ['id' => ':userId']) }}'.replace(':userId',
                    userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus === 1 ? 'Active' : 'In Active';
                    var buttonClass = newStatus === 1 ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
                    if (newStatus == 0) {
                        statusText = "In Active";
                        statusClass = "text-danger";
                    } else {
                        statusText = "Active";
                        statusClass = "text-success";
                    }
                    statusCell.html('<span class="' + statusClass + '">' + statusText + '</span>');
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
    </script>
@endsection
