@extends('salesagent.layout.app')
@section('title', 'Request')
@section('content')
    {{-- Create Request Model  --}}
    <div class="modal fade" id="createRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createRequestForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Enter Amount</label>
                                <input type="number" class="form-control name" id="amount" name="amount" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="createRequest()">Create</button>
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
                                    <h4>Withdrawal Request</h4>
                                </div>
                            </div>
                            <div class="row col-12 mt-3 d-flex justify-content-start">
                                <div class="form-group col-sm-3 mb-2">
                                    <label for="periodSelect">Withdrawal Status</label>
                                    <select id="periodSelect" class="form-control" onchange="loadData()">
                                        <option value="requested" selected>Requested</option>
                                        <option value="approved">Approved</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createRequestModal">
                                    Create Request
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Amount</th>
                                            <th>Status</th>
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
            dataTable.ajax.url("{{ route('user-request.get') }}?status=" + status).load();
        }
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('user-request.get') }}?status=requested",
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
                        "data": "amount",
                        "render": function(data, type, row, meta) {
                            return '$' + data;
                        },
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data === "requested") {
                                return '<span style="color: red;">Requested</span>';
                            } else if (data === "approved") {
                                return '<span style="color: green;">Approved</span>';
                            } else {
                                return data;
                            }
                        }
                    },

                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editRequestModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteRequestModal(id);
            });
        });
    </script>

    <script>
        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createSubadminForm input, #createSubadminForm select, #createSubadminForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createRequest() {
            var formData = new FormData($('#createRequestForm')[0]);
            var createButton = $('#createRequestModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);

            $.ajax({
                url: '{{ route('user-request.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#createRequestModal').modal('hide');
                    reloadDataTable();
                    $('#createRequestModal form')[0].reset();
                },
                error: function(xhr) {
                    createButton.prop('disabled', false);

                    if (xhr.status === 400 || xhr.status === 500 || 422) {

                        toastr.error(xhr.responseJSON.message || "An error occurred." || xhr.responseJSON
                            .message);
                    } else {
                        console.log("Unexpected Error:", xhr);
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }

        $('#createRequestForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        $('#createRequestModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        $('#createRequestModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
    </script>

@endsection
