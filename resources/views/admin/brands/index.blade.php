@extends('admin.layout.app')
@section('title', 'Brands')
@section('content')
    {{-- Create Brands Model  --}}
    <div class="modal fade" id="createBrandsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Brands</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createBrandsForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control slug" name="slug">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="status" class="form-control status">
                                    <option value="1">Active</option>
                                    <option value="0">In Active</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control image" name="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createBrands()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Brands Modal -->
    <div class="modal fade" id="editBrandsModal" tabindex="-1" role="dialog" aria-labelledby="editBrandsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandsModalLabel">Edit Brands</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editBrands" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control slug" name="slug">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="status" class="form-control status">
                                    <option value="1">Active</option>
                                    <option value="0">In Active</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control image" name="image">
                                <img id="imagePreview" src="" alt="Image Preview"
                                    style="display: none; max-width: 100px; margin-top: 10px;">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateCategories()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Brands Modal -->
    <div class="modal fade" id="deleteBrandsModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteBrandsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBrandsModalLabel">Delete Brands</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Brands?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" id="confirmDeleteSubadmin">Delete</button>
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
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Brands</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createBrandsModal">
                                    Create Brands
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
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

@section('js')

    {{-- Data Table --}}
    <script>
        // Convert Name into Slug
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".name").on("focusout", function(e) {
                    $(".slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
        // ######### Data Table ##############
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('brands.get') }}",
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
                        "data": "image",
                        "render": function(data, type, row) {
                            if (data) {
                                return '<img src="https://ranglerzclients.pw/macromed/' + data +
                                    '" alt="Image" style="width: 50px; height: 50px;">';
                            } else {
                                return '<img src="https://ranglerzclients.pw/macromed/public/admin/assets/images/users/admin.png" alt="Image" style="width: 50px; height: 50px;">';
                            }
                        }
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
                            return '<button class="btn btn-success  mr-2 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger  mr-2 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editBrandsModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteBrandsModal(id);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createBrandsForm input, #createBrandsForm select, #createBrandsForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createBrands() {
            var formData = new FormData($('#createBrandsForm')[0]);
            var createButton = $('#createBrandsModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('brands.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Brands Created Successfully!');
                    $('#createBrandsModal').modal('hide');
                    reloadDataTable();
                    $('#createBrandsModal form')[0].reset();
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
        $('#createBrandsForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        // ######Get & Update Brands#########

        function editBrandsModal(id) {
            var showBrands = '{{ route('brands.show', ':id') }}';
            $.ajax({
                url: showBrands.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editBrands .name').val(response.name);
                    $('#editBrands .slug').val(response.slug);
                    $('#editBrands .status').val(response.status);
                    var imageUrl = response.image;
                    var baseUrl = 'https://ranglerzclients.pw/macromed/';
                    var responseImage = baseUrl + response.image;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }
                    $('#editBrandsModal').modal('show');
                    $('#editBrandsModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editBrands input, #editBrands select, #editBrands textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateCategories() {
            var updateBrands = '{{ route('brands.update', ':id') }}';
            var id = $('#editBrandsModal').data('id');
            var formData = new FormData($('#editBrands')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateBrands.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Brands Updated Successfully!');
                    reloadDataTable();
                    $('#editBrandsModal').modal('hide');
                    $('#editBrands form')[0].reset();

                },
                error: function(xhr, status, error) {
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
        // ############# Delete Brands Data###########
        function deleteBrandsModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteBrandsModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteBrands(id)
            });
        });

        function deleteBrands(id) {
            $.ajax({
                url: "{{ route('brands.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Brands Deleted Successfully!');
                    $('#deleteBrandsModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        // ################ Active and Inactive code ############

        $('#example').on('click', '#update-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentStatus = button.text().trim().toLowerCase();
            var newStatus = currentStatus === 'Active' ? '1' : '0';
            button.prop('disabled', true);

            $.ajax({
                url: '{{ route('brandsBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus === '1' ? 'Active' : 'In Active';
                    var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
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
