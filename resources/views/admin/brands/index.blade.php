@extends('admin.layout.app')
@section('title', 'Brands')
@section('content')
    {{-- Create Brands Model  --}}
    <div class="modal fade createBrandsModal" id="createBrandsModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Brands</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createBrandsForm" enctype="multipart/form-data">
                        <input type="hidden" id="draft_id" name="draft_id">
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Brand Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control name" id="name" name="name" required
                                        oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Brand Owner Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control owner" id="owner" name="owner" required
                                        oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Company Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control company" id="company" name="company"
                                        required oninput="autosaveCategory()">

                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Company Country<span class="text-danger">*</span></label>
                                <select name="company_country" class="form-control select2 company_country" id="country"
                                    style="width: 100%" onchange="autosaveCategory()">
                                    <option value="" selected disabled>Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->iso2 . ',' . $country->name }}">
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @if ($countries == null)
                                    <div class="internet-error text-danger">No Internet Connection Found!</div>
                                @endif
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Contact Details<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control contact_detail" id="contact_detail"
                                        name="contact_detail" required oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="slug">Slug<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control slug" id="slug" name="slug">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="status">Active Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="0">In Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="image">Image<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control image" name="image"
                                        onchange="autosaveCategory()">
                                    <label for="imagePreview">Pervious Image:<span class="text-danger">*</span></label>
                                    <img id="imagePreview" src="" alt="Image Preview"
                                        style="display: none; max-width: 100px; margin-top: 10px;">
                                    <div class="invalid-feedback"></div>
                                </div>
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
                                <div class="form-group col-sm-3 mb-3 px-0">
                                    <label for="periodSelect">Visibility Status</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="1" selected><span class="text-danger">Published Data</span>
                                        </option>
                                        <option value="0">Draft Data</option>
                                    </select>
                                </div>
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createBrandsModal">
                                    Create Brands
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Owner Name</th>
                                            <th>Company Name</th>
                                            <th>Company Country</th>
                                            <th>Image</th>
                                            <th>Discounts</th>
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

    {{-- Data Table --}}
    <script>
        // ######### Data Table ##############
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('brands.get') }}?is_draft=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('brands.get') }}?is_draft=1",
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
                        "data": "name",
                        "render": function(data, type, row) {
                            return data ? data : 'No Name Found!';
                        }
                    },
                    {
                        "data": "owner",
                        "render": function(data, type, row) {
                            return data ? data : 'No Owner Found!';
                        }
                    },
                    {
                        "data": "company",
                        "render": function(data, type, row) {
                            return data ? data : 'No Company Found!';
                        }
                    },
                    {
                        "data": "company_country",
                        "render": function(data) {
                            if (!data || data.trim() === "") {
                                return "No Country Found!</span>";
                            }
                            return data.split(',')[1] ||
                                data;
                        }
                    },
                    {
                        "data": "image",
                        "render": function(data, type, row) {
                            if (data) {
                                return '<img src="https://macromed.com.pk/admin/' + data +
                                    '" alt="Image" style="width: 50px; height: 50px;">';
                            } else {
                                return '<img src="https://macromed.com.pk/admin/public/admin/assets/images/users/admin.png" alt="Image" style="width: 50px; height: 50px;">';
                            }
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('brandDiscounts.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 text-white"><i class="fas fa-tag"></i></a>';
                        },
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
                editBrandsModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteBrandsModal(id);
            });
        });
    </script>
    <script>
        // ##############Create Sub admin################
        function initializeSelect2(modal) {
            modal.find('.select2').select2({
                dropdownParent: modal,
                width: '100%'
            });
        }
        $('#createBrandsModal').on('shown.bs.modal', function() {
            initializeSelect2($(this));
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
                const formData = new FormData($('#createBrandsForm')[0]);
                var formDataObject = {};
                formData.forEach(function(value, key) {
                    formDataObject[key] = value;
                });
                const draftId = $('#draft_id').val();
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('brands.autosave') }}',
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
                        console.error('Autosave error:', xhr.responseText);
                    },
                });
            }, 1000); // 1-second debounce
        }

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
                    $('#draft_id').val('');
                    reloadDataTable();
                    $('#createBrandsModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
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

        // ######Get & Update Brands#########

        function editBrandsModal(id) {
            var showBrands = '{{ route('brands.show', ':id') }}';
            $.ajax({
                url: showBrands.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#createBrandsModal .name').val(response.name);
                    $('#createBrandsModal .slug').val(response.slug);
                    $('#createBrandsModal .status').val(response.status);
                    $('#createBrandsModal .owner').val(response.owner);
                    $('#createBrandsModal .company').val(response.company);
                    $('#createBrandsModal .company_country').val(response.company_country).trigger('change');
                    $('#createBrandsModal .contact_detail').val(response.contact_detail);
                    var imageUrl = response.image;
                    var baseUrl = 'https://macromed.com.pk/admin/';
                    var responseImage = baseUrl + response.image;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }
                    $('#createBrandsModal .modal-title').text('Edit'); // Change title to Edit
                    $('#createBrandsModal .btn-success').text('Publish'); // Change button text to Update
                    $('#draft_id').val(response.id);
                    $('#createBrandsModal').modal('show');

                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
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
