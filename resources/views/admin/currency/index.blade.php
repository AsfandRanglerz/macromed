@extends('admin.layout.app')
@section('title', 'Currency')
@section('content')
    {{-- Create Currency Model  --}}
    <div class="modal fade" id="createCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Currency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createCurrencyForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="doller_amount"> Enter Doller Amount</label>
                                <input type="text" class="form-control" id="pkr_amount" name="doller_amount" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="pkr_amount"> Enter PKR Amount</label>
                                <input type="text" class="form-control" id="pkr_amount" name="pkr_amount" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createCurrency()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Currency Modal -->
    <div class="modal fade" id="editCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="editCurrencyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCurrencyModalLabel">Edit Currency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCurrency" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="doller_amount"> Enter Doller Amount</label>
                                <input type="number" class="form-control doller_amount" name="doller_amount" required disabled>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="pkr_amount"> Enter PKR Amount</label>
                                <input type="number" class="form-control pkr_amount" name="pkr_amount" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateCurrency()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Currency Modal -->
    <div class="modal fade" id="deleteCurrencyModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteCurrencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCurrencyModalLabel">Delete Currency</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Currency?</h5>
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
                                    <h4>Currency</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                {{-- <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createCurrencyModal">
                                    Create Currency
                                </a> --}}
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Doller Amount</th>
                                            <th>PKR Amount</th>
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
                    "url": "{{ route('currency.get') }}",
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
                        "data": "doller_amount",
                        "render": function(data, type, row) {
                            return '$' + data;
                        }
                    },
                    {
                        "data": "pkr_amount",
                        "render": function(data, type, row) {
                            return 'Rs:' + data;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success  mr-2 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' ;
                                // '<button class="btn btn-danger  mr-2 text-white deleteSubadminBtn" data-id="' +
                                // row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editCurrencyModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteCurrencyModal(id);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createCurrencyForm input, #createCurrencyForm select, #createCurrencyForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createCurrency() {
            var formData = new FormData($('#createCurrencyForm')[0]);
            var createButton = $('#createCurrencyModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('currency.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Currency Created Successfully!');
                    $('#createCurrencyModal').modal('hide');
                    reloadDataTable();
                    $('#createCurrencyModal form')[0].reset();
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
        $('#createCurrencyForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        // ######Get & Update Currency#########

        function editCurrencyModal(id) {
            var showCurrency = '{{ route('currency.show', ':id') }}';
            $.ajax({
                url: showCurrency.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editCurrency .doller_amount').val(response.doller_amount);
                    $('#editCurrency .pkr_amount').val(response.pkr_amount);
                    $('#editCurrencyModal').modal('show');
                    $('#editCurrencyModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editCurrency input, #editCurrency select, #editCurrency textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateCurrency() {
            var updateCurrency = '{{ route('currency.update', ':id') }}';
            var id = $('#editCurrencyModal').data('id');
            var formData = new FormData($('#editCurrency')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateCurrency.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Currency Updated Successfully!');
                    $('#editCurrencyModal').modal('hide');
                    reloadDataTable();
                    $('#editCurrencyModal form')[0].reset();

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
        // ############# Delete Currency Data###########
        function deleteCurrencyModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteCurrencyModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteCurrency(id)
            });
        });

        function deleteCurrency(id) {
            $.ajax({
                url: "{{ route('currency.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Currency Deleted Successfully!');
                    $('#deleteCurrencyModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>

@endsection
