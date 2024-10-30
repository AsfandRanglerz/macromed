@extends('admin.layout.app')
@section('title', 'WithDraw Limit')
@section('content')
    {{-- Create WithDrawWalletLimit Model  --}}
    {{-- <div class="modal fade" id="createWithDrawWalletLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add WithDraw Wallet Limit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createWithDrawWalletLimitForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Enter Wallet WithDraw Minimum Limit</label>
                                    <input type="text" class="form-control" id="min_limits" name="min_limits" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Enter Wallet WithDraw Maximum Limit</label>
                                    <input type="text" class="form-control" id="max_limits" name="max_limits" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="createWithDrawWalletLimit()">Create</button>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Edit WithDrawWalletLimit Modal -->
    <div class="modal fade" id="editWithDrawWalletLimitModal" tabindex="-1" role="dialog"
        aria-labelledby="editWithDrawWalletLimitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWithDrawWalletLimitModalLabel">Enter Wallet WithDraw Limit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editWithDrawWalletLimit" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Enter Wallet WithDraw Minimum Limit</label>
                                    <input type="text" class="form-control min_limits" name="min_limits" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Enter Wallet WithDraw Maximum Limit</label>
                                    <input type="text" class="form-control max_limits" name="max_limits" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="updateWithDrawWalletLimits()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete WithDrawWalletLimit Modal -->
    {{-- <div class="modal fade" id="deleteWithDrawWalletLimitModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteWithDrawWalletLimitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteWithDrawWalletLimitModalLabel">Delete Subadmin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Wallet WithDraw Limit?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Wallet WithDraw Limit</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                {{-- <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createWithDrawWalletLimitModal">
                                    Create WithDraw Wallet Limit
                                </a> --}}
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Wallet WithDraw Minimum Limit</th>
                                            <th>Wallet WithDraw Maximum Limit</th>
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
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('withdrawLimit.get') }}",
                    "type": "POST",
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
                        "data": "min_limits",
                        "render": function(data) {
                            if (data != null && typeof data !== 'undefined') {
                                return 'Rs : '+ data;
                            } else {

                                return '';
                            }
                        }
                    },
                    {
                        "data": "max_limits",
                        "render": function(data) {
                            if (data != null && typeof data !== 'undefined') {
                                return 'Rs : '+ data;
                            } else {

                                return '';
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' ;
                                // '<button class="btn btn-danger mb-3 mr-3 text-white deleteSubadminBtn" data-id="' +
                                // row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editWithDrawWalletLimitModal(id);
            });
            // $('#example').on('click', '.deleteSubadminBtn', function() {
            //     var id = $(this).data('id');
            //     deleteWithDrawWalletLimitModal(id);
            // });
        });
    </script>

    <script>
        // ##############Create Sub admin################
        // function createWithDrawWalletLimit() {
        //     var formData = new FormData($('#createWithDrawWalletLimitForm')[0]);
        //     var createButton = $('#createWithDrawWalletLimitModal').find('.modal-footer').find('button');
        //     createButton.prop('disabled', true);
        //     var formDataObject = {};
        //     formData.forEach(function(value, key) {
        //         formDataObject[key] = value;
        //     });
        //     $.ajax({
        //         url: '{{ route('withDrawLimit.create') }}',
        //         type: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             Toast.fire({
        //                 icon: response.alert,
        //                 title: response.message
        //             });
        //             $('#createWithDrawWalletLimitModal').modal('hide');
        //             reloadDataTable();
        //             $('#createWithDrawWalletLimitForm')[0].reset();
        //         },
        //         error: function(xhr, status, error) {
        //             console.log(xhr.responseText);
        //             var errors = xhr.responseJSON.errors;
        //             $.each(errors, function(key, value) {
        //                 $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
        //                     value[
        //                         0]);
        //             });
        //         },
        //         complete: function() {
        //             createButton.prop('disabled', false);
        //         }
        //     });
        // }
        // $('#createWithDrawWalletLimitForm input').keyup(function() {
        //     $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        // });

        // ######Get & Update WithDrawWalletLimit#########
        var showWithDrawWalletLimit = '{{ route('withDrawLimit.show', ':id') }}';
        var updateWithDrawWalletLimit = '{{ route('withDrawLimit.update', ':id') }}';

        function editWithDrawWalletLimitModal(id) {
            $.ajax({
                url: showWithDrawWalletLimit.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editWithDrawWalletLimit .min_limits').val(response.min_limits);
                    $('#editWithDrawWalletLimit .max_limits').val(response.max_limits);
                    $('#editWithDrawWalletLimitModal').modal('show');
                    $('#editWithDrawWalletLimitModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        function updateWithDrawWalletLimits() {
            var id = $('#editWithDrawWalletLimitModal').data('id');
            var formData = new FormData($('#editWithDrawWalletLimit')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateWithDrawWalletLimit.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    $('#editWithDrawWalletLimitModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {

                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                            value[
                                0]);
                    });
                }
            });
        }
        // ############# Delete WithDrawWalletLimit Data###########
        // function deleteWithDrawWalletLimitModal(id) {
        //     $('#confirmDeleteSubadmin').data('subadmin-id', id);
        //     $('#deleteWithDrawWalletLimitModal').modal('show');
        // }
        // $(document).ready(function() {
        //     $('#confirmDeleteSubadmin').click(function() {
        //         var id = $(this).data('subadmin-id');
        //         deleteWithDrawWalletLimit(id)
        //     });
        // });

        // function deleteWithDrawWalletLimit(id) {
        //     $.ajax({
        //         url: "{{ route('withDrawLimit.delete', ['id' => ':id']) }}".replace(':id', id),
        //         type: 'GET',
        //         success: function(response) {
        //             Toast.fire({
        //                 icon: response.alert,
        //                 title: response.message
        //             });
        //             $('#deleteWithDrawWalletLimitModal').modal('hide');
        //             reloadDataTable();
        //         },
        //         error: function(xhr, status, error) {
        //             console.log(xhr.responseText);
        //         }
        //     });
        // }
    </script>

@endsection
