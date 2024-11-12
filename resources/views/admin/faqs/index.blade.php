@extends('admin.layout.app')
@section('title', 'FAQ')
@section('content')

    {{-- Create FAQ Model  --}}
    <div class="modal fade" id="createFAQModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createFAQForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Question</label>
                                <input type="text" class="form-control" id="questions" name="questions" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Answer <span class="text-danger">*</span></label>
                                <textarea name="answers" cols="50" rows="5" id="answers" class="form-control edit-description"></textarea>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success button-color" onclick="createFAQ()">Create</button>
                </div>
            </div>
            <script>
                let geteditor;
                ClassicEditor
                    .create(document.querySelector('.edit-description'))
                    .then(newGetEditor => {
                        geteditor = newGetEditor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>
        </div>
    </div>
    <!-- Edit FAQ Modal -->
    <div class="modal fade" id="editFAQModal" tabindex="-1" role="dialog" aria-labelledby="editFAQModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFAQModalLabel">Edit FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editFAQ" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Question</label>
                                <input type="text" class="form-control questions" name="questions" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Answer <span class="text-danger">*</span></label>
                                <textarea name="answers" cols="20" rows="50" id="answers" class="description form-control edit-description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success button-color" onclick="updateFAQ()">Update</button>
                </div>
                <script>
                    let editor;
                    ClassicEditor
                        .create(document.querySelector('.description'))
                        .then(newGetEditor => {
                            editor = newGetEditor;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                </script>

            </div>
        </div>
    </div>
    <!-- Delete FAQ Modal -->
    <div class="modal fade" id="deleteFAQModal" tabindex="-1" role="dialog" aria-labelledby="deleteFAQModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFAQModalLabel">Delete FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this FAQ?</h5>
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
                                    <h4>FAQ`s</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-success mb-3 button-color text-white" data-toggle="modal"
                                    data-target="#createFAQModal">
                                    Create FAQ
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <i class="fas fa-th"></i>
                                            </th>
                                            <th>Sr.</th>
                                            <th>Question</th>
                                            <th>Answers</th>
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
                    "url": "{{ route('faq.get') }}",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return '<div class="sort-handler"><i class="fas fa-th"></i></div>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "questions"
                    },
                    {
                        "data": "answers",
                        "render": function(data, type, row) {
                            var words = data.split(" ");
                            if (words.length > 15) {
                                return words.slice(0, 10).join(" ") + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<div class="d-flex justify-content-start">' +'<button class="btn btn-success button-color mr-2 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger  mr-2 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>'+
                                '</div>';
                        }
                    }
                ]
            });

            // Enable drag-and-drop
            $('#example tbody').sortable({
                items: 'tr',
                cursor: 'move',
                update: function(event, ui) {
                    var order = [];
                    $('#example tbody tr').each(function(index) {
                        var id = $(this).find('.editSubadminBtn').data('id');
                        order.push({
                            id: id,
                            position: index + 1
                        });
                    });

                    // Send updated order to server
                    $.ajax({
                        url: "{{ route('faq.updateOrder') }}", // Update this route
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function(response) {
                            reloadDataTable()
                            // toastr.success("Order updated successfully!");
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            toastr.error("Error updating order!");
                        }
                    });
                }
            }).disableSelection();

            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editFAQModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteFAQModal(id);
            });
        });


        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createFAQForm input, #createFAQForm select, #createFAQForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createFAQ() {
            var form = document.getElementById("createFAQForm");
            var questions = form["questions"].value;
            var answers = geteditor.getData();
            var formData = new FormData();
            formData.append('questions', questions);
            formData.append('answers', answers);

            var createButton = $('#createFAQModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('faq.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('FAQ Created Successfully!');
                    $('#createFAQModal').modal('hide');
                    geteditor.setData('');
                    reloadDataTable();
                    $('#createFAQModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Show each validation error in a toastr notification
                            toastr.error(value[0]); // Display the first error for each field
                        });
                    } else {
                        console.log("Error:", xhr);
                        toastr.error(
                            "An unexpected error occurred. Please try again."); // General error message
                    }

                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        $('#createFAQForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        // ######Get & Update FAQ#########

        function editFAQModal(id) {
            var showFAQ = '{{ route('faq.show', ':id') }}';
            $.ajax({
                url: showFAQ.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editFAQ .questions').val(response.questions);
                    editor.setData(response.answers);
                    $('#editFAQModal').modal('show');
                    $('#editFAQModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editFAQ input, #editFAQ select, #editFAQ textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateFAQ() {
            var updateFAQ = '{{ route('faq.update', ':id') }}';
            var id = $('#editFAQModal').data('id');
            var form = document.getElementById("editFAQ");
            var questions = form["questions"].value;
            var answers = editor.getData();
            var formData = new FormData();
            formData.append('questions', questions);
            formData.append('answers', answers);

            $.ajax({
                url: updateFAQ.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('FAQ Updated Successfully!');
                    $('#editFAQModal').modal('hide');
                    reloadDataTable();
                    $('#editFAQModal form')[0].reset();

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Show each validation error in a toastr notification
                            toastr.error(value[0]); // Display the first error for each field
                        });
                    } else {
                        console.log("Error:", xhr);
                        toastr.error(
                            "An unexpected error occurred. Please try again."); // General error message
                    }

                }
            });
        }
        // ############# Delete FAQ Data###########
        function deleteFAQModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteFAQModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteFAQ(id)
            });
        });

        function deleteFAQ(id) {
            $.ajax({
                url: "{{ route('faq.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('FAQ Deleted Successfully!');
                    $('#deleteFAQModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>


@endsection
