@extends('admin.layout.app')
@section('title', 'PrivateNotes')
@section('content')
    {{-- Create PrivateNotes Model  --}}
    <div class="modal fade" id="createPrivateNotesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Private Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createPrivateNotesForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Long Description <span class="text-danger">*</span></label>
                                <textarea name="description" cols="20" rows="50"  id="description"></textarea>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createPrivateNotes()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit PrivateNotes Modal -->
    <div class="modal fade" id="editPrivateNotesModal" tabindex="-1" role="dialog"
        aria-labelledby="editPrivateNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPrivateNotesModalLabel">Edit Private Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPrivateNotes" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" class="form-control title" name="title" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Long Description <span class="text-danger">*</span></label>
                                <textarea name="description" cols="20" rows="50" class="long_description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updatePrivateNotes()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete PrivateNotes Modal -->
    <div class="modal fade" id="deletePrivateNotesModal" tabindex="-1" role="dialog"
        aria-labelledby="deletePrivateNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePrivateNotesModalLabel">Delete Private Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Private Notes?</h5>
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
                                    <h4>PrivateNotes</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createPrivateNotesModal">
                                    Create Private Notes
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
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
                    "url": "{{ route('privateNotes.get') }}",
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
                        "data": "title"
                    },
                    {
                        "data": "description"
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
                editPrivateNotesModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deletePrivateNotesModal(id);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createPrivateNotesForm input, #createPrivateNotesForm select, #createPrivateNotesForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createPrivateNotes() {
            var form = document.getElementById("createPrivateNotesForm");
            var title = form["title"].value;
            var description = editor.getData();
            var formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);

            var createButton = $('#createPrivateNotesModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('privateNotes.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Private Notes Created Successfully!');
                    $('#createPrivateNotesModal').modal('hide');
                    reloadDataTable();
                    $('#createPrivateNotesModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                                value[
                                    0]);
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
        $('#createPrivateNotesForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        // ######Get & Update PrivateNotes#########

        function editPrivateNotesModal(id) {
            var showPrivateNotes = '{{ route('privateNotes.show', ':id') }}';
            $.ajax({
                url: showPrivateNotes.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editPrivateNotes .title').val(response.title);
                    if (response.description !== null) {
                        editors.setData(response.description);
                    } else {
                        editors.setData('');
                    }
                    $('#editPrivateNotesModal').modal('show');
                    $('#editPrivateNotesModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editPrivateNotes input, #editPrivateNotes select, #editPrivateNotes textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updatePrivateNotes() {
            var updatePrivateNotes = '{{ route('privateNotes.update', ':id') }}';
            var id = $('#editPrivateNotesModal').data('id');
            var form = document.getElementById("editPrivateNotes");
            var formData = new FormData(form);
            formData.append('description', editors ? editors.getData() : '');
            $.ajax({
                url: updatePrivateNotes.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Private Notes Updated Successfully!');
                    $('#editPrivateNotesModal').modal('hide');
                    reloadDataTable();
                    $('#editPrivateNotesModal form')[0].reset();

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
        // ############# Delete PrivateNotes Data###########
        function deletePrivateNotesModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deletePrivateNotesModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deletePrivateNotes(id)
            });
        });

        function deletePrivateNotes(id) {
            $.ajax({
                url: "{{ route('privateNotes.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('PrivateNotes Deleted Successfully!');
                    $('#deletePrivateNotesModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        let editor;
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
