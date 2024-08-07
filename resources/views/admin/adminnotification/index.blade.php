@extends('admin.layout.app')
@section('title', 'Notification')
@section('content')
    {{-- Create AdminNotification Model  --}}
    <div class="modal fade" id="createAdminNotificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createAdminNotificationForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Notification To </label>
                                <select name="user_name[]" id="user_name" class="form-control select2" multiple=""
                                    style="width: 100%">
                                    <option value="customer">Customers</option>
                                    <option value="salesmanager">Sales Agent</option>
                                </select>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea name="message" cols="20" rows="50" class="message"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createAdminNotification()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit AdminNotification Modal -->
    <div class="modal fade" id="editAdminNotificationModal" tabindex="-1" role="dialog"
        aria-labelledby="editAdminNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminNotificationModalLabel">Edit Admin Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAdminNotification" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Notification To </label>
                                <input type="text" class="form-control name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea name="message" cols="20" rows="50" class="message"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateAdminNotification()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete AdminNotification Modal -->
    <div class="modal fade" id="deleteAdminNotificationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteAdminNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAdminNotificationModalLabel">Delete Admin Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Admin Notification?</h5>
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
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Notification</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive text-center">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createAdminNotificationModal">
                                    Create Notification
                                </a>
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



        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createAdminNotificationForm input, #createAdminNotificationForm select, #createAdminNotificationForm textarea')
                .on(
                    'input change',
                    function() {
                        $(this).siblings('.invalid-feedback').text('');
                        $(this).removeClass('is-invalid');
                    });
        });

        function createAdminNotification() {
            var user_name = [];
            $('select[name="user_name[]"] option:selected').each(function(index, element) {
                user_name.push($(element).val());
            });
            var message = editor.getData();
            var formData = new FormData();
            formData.append('user_name', JSON.stringify(user_name)); // Convert array to JSON
            formData.append('message', message);
            var createButton = $('#createAdminNotificationModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);

            $.ajax({
                url: '{{ route('adminNotification.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Notification Sent Successfully!');
                    $('#createAdminNotificationModal').modal('hide');
                    if (editor) {
                        editor.setData('');
                    }
                    var $select = $('#user_name');
                    $select.val(null).trigger('change');
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while creating the notification.');
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }

        $('#createAdminNotificationForm input, #createAdminNotificationForm textarea').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });


        let editor;
        ClassicEditor
            .create(document.querySelector('.message'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
