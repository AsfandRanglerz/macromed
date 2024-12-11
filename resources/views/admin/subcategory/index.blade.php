@extends('admin.layout.app')
@section('title', 'Sub Category')
@section('content')
    {{-- Create Category Model  --}}
    <div class="modal fade" id="createSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSubCategoryForm" enctype="multipart/form-data">
                        <input type="hidden" id="draft_id" name="draft_id">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="category">Category<span class="text-danger">*</span></label>
                                <select class="form-control select2" id="category_id" name="category_id" required
                                    style="width: 100%" onchange="autosaveCategory()">
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    oninput="autosaveCategory()">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="status" class="form-control" id="status" onchange="autosaveCategory()">
                                    <option value="0">In Active</option>
                                    <option value= "1">Active</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="saveSubCategory()">Create</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteSubCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSubCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubCategoryModalLabel">Delete Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Sub Category?</h5>
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
                                    <h4>Sub Category</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <div class="form-group col-sm-3 mb-3 px-0">
                                    <label for="periodSelect">Visibility Status</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="1" selected><span class="text-danger">Published Data</span>
                                        </option>
                                        <option value="0">Draft Data</option>
                                    </select>
                                </div>
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createSubCategoryModal">
                                    Create Sub Category
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category Name</th>
                                            <th>Sub Category Name</th>
                                            <th>Visibility Status</th>
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
        // Data Table
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('subCategory.get') }}?is_draft=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('subCategory.get') }}?is_draft=1",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    },

                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "category.name",
                        "render": function(data, type, row) {
                            return data ? data : 'No Category Found';
                        }
                    },
                    {
                        "data": "name",
                        "render": function(data, type, row) {
                            return data ? data : 'No Name Found';
                        }
                    },
                    {
                        "data": "is_draft",
                        "render": function(data, type, row) {
                            if (data == 0) {
                                return '<span class ="text-danger">In-Darft</span>'
                            } else {
                                return '<span class ="text-success">Published</span>'
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            // Check if is_draft is 1 (published), then show Active/Inactive button
                            if (row.is_draft == 1) {
                                var buttonClass = row.status == '1' ? 'btn-success' : 'btn-danger';
                                var buttonText = row.status == '1' ? 'Active' : 'In Active';
                                return '<button id="update-status" class="btn ' + buttonClass +
                                    '" data-userid="' + row.id + '">' + buttonText + '</button>';
                            } else {
                                // If it's not published, do not display the button
                                return '<span class="text-muted">No Active Status</span>';
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown${row.id}" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="actionDropdown${row.id}">
                    <a class="dropdown-item editSubadminBtn" href="#" data-id="${row.id}">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a class="dropdown-item deleteSubadminBtn text-danger" href="#" data-id="${row.id}">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </a>
                </div>
            </div>
        `;
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editSubCategoryModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteSubCategoryModal(id);
            });
        });
    </script>
    <script>
        function initializeSelect2(modal) {
            modal.find('.select2').select2({
                dropdownParent: modal,
                width: '100%'
            });
        }
        $('#createSubCategoryModal').on('shown.bs.modal', function() {
            initializeSelect2($(this));
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        $('#createSubCategoryModal').on('hidden.bs.modal', function() {
            // $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#name").on("keyup", function() {
                    const nameValue = $(this).val();
                    const slugValue = convertToSlug(nameValue);
                    $("#slug").val(slugValue);
                    autosaveCategory();
                });
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }

        let autosaveTimer;

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const formData = new FormData($('#createSubCategoryForm')[0]);
                const draftId = $('#draft_id').val();
                $('#createSubCategoryForm').find('.is-invalid').removeClass('is-invalid');
                $('#createSubCategoryForm').find('.invalid-feedback').removeClass('.invalid-feedback');
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('subCategory.autosave') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#draft_id').val(response.draft_id);
                    },
                    error: function(xhr) {
                        // console.error('Autosave error:', xhr.responseText);
                        if (xhr.status === 422) { // Validation error
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid').siblings(
                                    '.invalid-feedback').html(value[0]);
                            });
                        } else {
                            // console.error(xhr.responseText);
                        }
                    },
                });
            }, 1000); // 1-second debounce
        }

        function saveSubCategory() {
            var formData = new FormData($('#createSubCategoryForm')[0]);
            const url = '{{ route('subCategory.create') }}';
            const method = 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#createSubCategoryModal').modal('hide');
                    $('#draft_id').val('');
                    $('#createSubCategoryForm')[0].reset();
                    reloadDataTable(); // Reload the DataTable to reflect changes
                },
                error: function(xhr, status, error) {
                    // console.error('Autosave error:', xhr.responseText);
                    if (xhr.status === 422) { // Validation error
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings(
                                '.invalid-feedback').html(value[0]);
                        });
                    } else {
                        // console.error(xhr.responseText);
                    }
                }
            });
        }

        function editSubCategoryModal(id) {
            var showCategory = '{{ route('subCategory.show', ':id') }}';
            $.ajax({
                url: showCategory.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#name').val(response.name);
                    $('#slug').val(response.slug);
                    $('#status').val(response.status);
                    $('#category_id').val(response.category_id).trigger('change');
                    $('#createSubCategoryModal .modal-title').text('Edit'); // Change title to Edit
                    $('#createSubCategoryModal .btn-success').text('Publish'); // Change button text to Update
                    $('#draft_id').val(response.id);
                    $('#createSubCategoryModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }





        // ############# Delete Category Data###########
        function deleteSubCategoryModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteSubCategoryModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteSubCategory(id)
            });
        });

        function deleteSubCategory(id) {
            $.ajax({
                url: "{{ route('subCategory.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Sub Category Deleted Successfully!');
                    $('#deleteSubCategoryModal').modal('hide');
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
                url: '{{ route('subcategoryBlock.update', ['id' => ':userId']) }}'.replace(':userId',
                    userId),
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
