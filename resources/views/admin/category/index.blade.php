@extends('admin.layout.app')
@section('title', 'Category')
@section('content')

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
                        <input type="hidden" id="draft_id" name="draft_id">
                        {{-- <input type="hidden" id="category_id" name="category_id"> --}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                oninput="autosaveCategory()">
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug">
                        </div>
                        <div class="form-group">
                            <label for="status">Active Status</label>
                            <select name="status" class="form-control" id="status" onchange="autosaveCategory()">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="saveCategoryBtn"
                        onclick="saveCategory()">Save</button>
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
                                <div class="form-group col-sm-3 mb-3 px-0">
                                    <label for="periodSelect">Draft Status</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="1" selected><span class="text-danger">Saved Data</span>
                                        </option>
                                        <option value="0">Draft Data</option>
                                    </select>
                                </div>
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createCategoryModal" onclick="initializeCreateCategoryModal()">Create
                                    Category</a>
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
        // Convert Name into Slug
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
        // ######### Data Table ##############
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }

        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('category.get') }}?is_draft=" + status).load();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('category.get') }}?is_draft=1",
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
        let autosaveTimer;

        function initializeCreateCategoryModal() {
            loadFromLocalStorage();
            const draftId = $('#draft_id').val();
            const url = `{{ url('admin/category/draft') }}/${draftId}`;
            // Fetch draft from server if a draft ID exists
            if (draftId) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#name').val(response.name);
                        $('#slug').val(response.slug);
                        $('#status').val(response.status);
                    },
                    error: function(xhr) {
                        console.error('Error fetching draft:', xhr.responseText);
                    },
                });
            }
        }
        // Save form data to local storage
        function saveToLocalStorage() {
            const formData = {
                name: $('#name').val(),
                slug: $('#slug').val(),
                status: $('#status').val(),
                draft_id: $('#draft_id').val(),
            };
            localStorage.setItem('categoryDraft', JSON.stringify(formData));
        }

        function loadFromLocalStorage() {
            const savedData = localStorage.getItem('categoryDraft');
            console.log("data", savedData);

            if (savedData) {
                const data = JSON.parse(savedData);
                $('#name').val(data.name || '');
                $('#slug').val(data.slug || '');
                $('#status').val(data.status || '0');
                $('#draft_id').val(data.draft_id || '');
            }
        }

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                var formData = new FormData($('#createCategoryForm')[0]);
                const draftId = $('#draft_id').val();

                if (draftId) {
                    formData.append('draft_id', draftId);
                }

                saveToLocalStorage(); // Save data to local storage

                $.ajax({
                    url: '{{ route('category.autosave') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        // console.log("data", response);
                        toastr.success(response.message);
                        $('#draft_id').val(response.draft_id); // Save draft ID
                        // console.log('Draft saved:', response.message);
                    },
                    error: function(xhr) {
                        console.error('Autosave error:', xhr.responseText);
                    },
                });
            }, 1000); // 1-second debounce
        }

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                var formData = new FormData($('#createCategoryForm')[0]);
                const draftId = $('#draft_id').val();
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('category.autosave') }}',
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
                        saveToLocalStorage();
                    },
                    error: function(xhr) {
                        console.error('Autosave error:', xhr.responseText);
                    },
                });
            }, 2000); // 1-second debounce
        }

        function editCategoryModal(id) {
            var showCategory = '{{ route('category.show', ':id') }}';
            $.ajax({
                url: showCategory.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#name').val(response.name);
                    $('#slug').val(response.slug);
                    $('#status').val(response.status);
                    $('#createCategoryModal .modal-title').text('Edit Category'); // Change title to Edit
                    $('#createCategoryModal .btn-success').text('Update'); // Change button text to Update
                    $('#draft_id').val(response.id);
                    $('#createCategoryModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function saveCategory() {
            var formData = new FormData($('#createCategoryForm')[0]);
            const url = '{{ route('category.create') }}';
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
                    $('#createCategoryModal').modal('hide');
                    $('#draft_id').val('');
                    localStorage.removeItem('categoryDraft');
                    $('#createCategoryForm')[0].reset();
                    reloadDataTable(); // Reload the DataTable to reflect changes
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // Validation error
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        console.error(xhr.responseText);
                    }
                }
            });
        }

        window.addEventListener('beforeunload', saveToLocalStorage);

        // ###### Get & Update Category #########




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
