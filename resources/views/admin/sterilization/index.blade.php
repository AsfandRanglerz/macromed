@extends('admin.layout.app')
@section('title', 'Sterilization')
@section('content')
    {{-- Create Sterilization Model  --}}
    <div class="modal fade" id="createSterilizationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sterilization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSterilizationForm" enctype="multipart/form-data">
                        <input type="hidden" id="draft_id" name="draft_id">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Enter Sterilization</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    oninput="autosaveCategory()">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="0">In Active</option>
                                    <option value="1">Active</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createSterilization()">Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Sterilization Modal -->
    <div class="modal fade" id="deleteSterilizationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSterilizationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSterilizationModalLabel">Delete Sterilization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Sterilization?</h5>
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
                                    <h4>Sterilization</h4>
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
                                    data-target="#createSterilizationModal">
                                    Create Sterilization
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
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
            dataTable.ajax.url("{{ route('sterilization.get') }}?is_draft=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('sterilization.get') }}?is_draft=1",
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
                editSterilizationModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteSterilizationModal(id);
            });
        });

        // ##############Create Sub admin################
        let autosaveTimer;

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const formData = new FormData($('#createSterilizationForm')[0]);
                var formDataObject = {};
                formData.forEach(function(value, key) {
                    formDataObject[key] = value;
                });
                const draftId = $('#draft_id').val();
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('sterilization.autosave') }}',
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

        function createSterilization() {
            var formData = new FormData($('#createSterilizationForm')[0]);
            var createButton = $('#createSterilizationModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('sterilization.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Sterilization Created Successfully!');
                    $('#createSterilizationModal').modal('hide');
                    $('#draft_id').val('');
                    reloadDataTable();
                    $('#createSterilizationModal form')[0].reset();
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
        // ######Get & Update Sterilization#########

        function editSterilizationModal(id) {
            var showSterilization = '{{ route('sterilization.show', ':id') }}';
            $.ajax({
                url: showSterilization.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#createSterilizationForm #name').val(response.name);
                    $('#createSterilizationForm #status').val(response.status);
                    $('#createSterilizationModal .modal-title').text('Edit');
                    $('#createSterilizationModal .btn-success').text('Publish');
                    $('#draft_id').val(response.id);
                    $('#createSterilizationModal').modal('show');
                    $('#editSterilizationModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }

        // ############# Delete Sterilization Data###########
        function deleteSterilizationModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteSterilizationModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteSterilization(id)
            });
        });

        function deleteSterilization(id) {
            $.ajax({
                url: "{{ route('sterilization.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Sterilization Deleted Successfully!');
                    $('#deleteSterilizationModal').modal('hide');
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
                url: '{{ route('sterilizationBlock.update', ['id' => ':userId']) }}'.replace(':userId',
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
