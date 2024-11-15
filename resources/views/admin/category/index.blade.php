@extends('admin.layout.app')
@section('title', 'Category')
@section('content')
    {{-- Create Category Model  --}}
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createCategoryForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control name" id="name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control slug" id="slug" name="slug">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">In Active</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createCategory()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategory" enctype="multipart/form-data">
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
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateCategories()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Category?</h5>
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
                                    <h4>Category</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createCategoryModal">
                                    Create Category
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Discounts</th>
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

    {{-- Data Table --}}
    <script>
        // ######### Data Table ##############
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('category.get') }}",
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
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('discounts.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 text-white"><i class="fas fa-tag"></i></a>';
                        },
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
                editCategoryModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteCategoryModal(id);
            });
        });
    </script>

    <script>
        // ##############Create Sub admin################
        const draftKey = 'categoryDraftData';

        // Utility function to save draft data to localStorage
        function saveDraftToLocal(formData, categoryId = null) {
            formData.categoryId = categoryId; // Save categoryId in draft
            localStorage.setItem(draftKey, JSON.stringify(formData));
        }

        // Utility function to clear form fields
        function clearCategoryForm() {
            $('#createCategoryForm').trigger('reset');
            $('#createCategoryForm .is-invalid').removeClass('is-invalid');
            $('#createCategoryForm .invalid-feedback').html('');
        }

        // Function to load draft data (from localStorage or server)
        function loadCategoryDraft() {
            let localDraft = JSON.parse(localStorage.getItem(draftKey));

            if (localDraft) {
                $('#name').val(localDraft.name || '');
                $('#slug').val(localDraft.slug || '');
                $('#status').val(localDraft.status || '1');

                // If there is a categoryId in the draft, use it
                if (localDraft.categoryId) {
                    $('#createCategoryForm').data('categoryId', localDraft.categoryId);
                }
            } else {
                // Fetch draft from the server if no local draft is found
                $.ajax({
                    url: '{{ route('category.getDraft') }}',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("data", response);
                        if (response.data) {
                            $('#name').val(response.data.name || '');
                            $('#slug').val(response.data.slug || '');
                            $('#status').val(response.data.status || '1');
                            // Save the categoryId
                            saveDraftToLocal(response.data, response.data.id);
                        } else {
                            clearCategoryForm();
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching draft:", xhr);
                    }
                });
            }
        }

        // Event listener to handle modal show and load category draft
        $('#createCategoryModal').on('show.bs.modal', function() {
            loadCategoryDraft();
        });

        // Event listener to save draft both locally and on the server
        $('#createCategoryForm').on('input', function() {
            let formData = {
                name: $('#name').val(),
                slug: $('#slug').val(),
                status: $('#status').val()
            };

            let categoryId = $('#createCategoryForm').data('categoryId');
            // Save draft to localStorage with categoryId
            saveDraftToLocal(formData, categoryId);

            // Delay server save to prevent frequent requests (debounced)
            clearTimeout(window.draftTimer);
            window.draftTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route('category.saveDraft') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success('Draft Saved');
                    },
                    error: function(xhr) {
                        console.error("Error saving draft:", xhr);
                    }
                });
            }, 2000); // Save draft every 2 seconds after user stops typing
        });

        // Function to create or update category and clear drafts after success
        function createCategory() {
            let formData = new FormData($('#createCategoryForm')[0]);
            let categoryId = $('#createCategoryForm').data('categoryId'); // Get categoryId from form data
            let createButton = $('#createCategoryModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: categoryId ? `{{ route('category.create', '') }}/${categoryId}` :
                    '{{ route('category.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Category Created/Updated Successfully!');
                    localStorage.removeItem(draftKey); // Clear draft data from localStorage
                    clearCategoryForm(); // Clear form fields
                    $('#createCategoryModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // Validation errors
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        console.error("Error:", xhr);
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        $(".name").on("focusout", function() {
            const name = $(this).val();
            $(".slug").val(convertToSlug(name));
        });
        let debounceTimer;
        $(".name").on("keyup", function(e) {
            const name = $(this).val();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                $(".slug").val(convertToSlug(name));
            }, 300);
        })

        function convertToSlug(text) {
            return text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        }
        window.addEventListener('beforeunload', function() {
            let formData = {
                name: $('#name').val(),
                slug: $('#slug').val(),
                status: $('#status').val()
            };
            saveDraftToLocal(formData);
        });


        // ######Get & Update Category#########

        function editCategoryModal(id) {
            var showCategory = '{{ route('category.show', ':id') }}';
            $.ajax({
                url: showCategory.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editCategory .name').val(response.name);
                    $('#editCategory .slug').val(response.slug);
                    $('#editCategory .status').val(response.status);
                    $('#editCategoryModal').modal('show');
                    $('#editCategoryModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editCategory input, #editCategory select, #editCategory textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateCategories() {
            var updateCategory = '{{ route('category.update', ':id') }}';
            var id = $('#editCategoryModal').data('id');
            var formData = new FormData($('#editCategory')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateCategory.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Category Updated Successfully!');
                    reloadDataTable();
                    $('#editCategoryModal').modal('hide');
                    $('#editCategory form')[0].reset();

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                                value[
                                    0]);
                        });
                    } else {
                        console.log("Error:", xhr);
                    }
                }
            });
        }
        $('#editCategoryModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });

        $('#editCategoryModal').on('show.bs.modal', function() {
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        // ############# Delete Category Data###########
        function deleteCategoryModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteCategoryModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteCategory(id)
            });
        });

        function deleteCategory(id) {
            $.ajax({
                url: "{{ route('category.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Category Deleted Successfully!');
                    $('#deleteCategoryModal').modal('hide');
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
                url: '{{ route('categoryBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
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
