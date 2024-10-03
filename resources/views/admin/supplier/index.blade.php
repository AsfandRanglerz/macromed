@extends('admin.layout.app')
@section('title', 'Supplier')
@section('content')
    {{-- Create Supplier Model  --}}
    <div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSupplierForm" enctype="multipart/form-data">
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier POC</label>
                                    <input type="text" class="form-control" id="poc" name="poc" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Physical Addres</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Whats App</label>
                                    <input type="text" class="form-control" id="whats_app" name="whats_app" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Website</label>
                                    <input type="text" class="form-control" id="website" name="website" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Alternate Email</label>
                                    <input type="text" class="form-control" id="alternate_email" name="alternate_email"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Alternate Phone Number</label>
                                    <input type="text" class="form-control" id="alternate_phone_number"
                                        name="alternate_phone_number" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Country</label>
                                <select name="country" class="form-control select2" id="country" style="width: 100%">
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
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <select class="form-control select2" id="state" name="state" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Select State</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <select class="form-control select2 " id="city" name="city" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group col-md-12 col-lg-12">
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
                    <button type="button" class="btn btn-success" onclick="createSupplier()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Supplier Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog"
        aria-labelledby="editSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSupplier" enctype="multipart/form-data">
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Name</label>
                                    <input type="text" class="form-control name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier POC</label>
                                    <input type="text" class="form-control poc" name="poc" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Email</label>
                                    <input type="email" class="form-control email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Supplier Phone Number</label>
                                    <input type="text" class="form-control phone_number" name="phone_number" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Physical Addres</label>
                                    <input type="text" class="form-control address" name="address" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Whats App</label>
                                    <input type="text" class="form-control whats_app" name="whats_app" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Website</label>
                                    <input type="text" class="form-control website" name="website" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Alternate Email</label>
                                    <input type="text" class="form-control alternate_email" name="alternate_email"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Alternate Phone Number</label>
                                    <input type="text" class="form-control alternate_phone_number"
                                        name="alternate_phone_number" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Country</label>
                                <select name="country" class="form-control select2 country" style="width: 100%">
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
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <select class="form-control select2 state" name="state" style="width: 100%" required>
                                    <option value="" selected disabled>Select State</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <select class="form-control select2 city" name="city" style="width: 100%" required>
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group col-md-12 col-lg-12">
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
                    <button type="button" class="btn btn-success" onclick="updateSupplier()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Supplier Modal -->
    <div class="modal fade" id="deleteSupplierModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSupplierModalLabel">Delete Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Supplier?</h5>
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
                                    <h4>Supplier</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                                    data-target="#createSupplierModal">
                                    Create Supplier
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Supplier Code</th>
                                            <th>POC</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Whats App</th>
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
                    "url": "{{ route('supplier.get') }}",
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
                        "data": "supplier_id",
                        "render": function(data, type, row) {
                            return '#' + data;
                        }
                    },
                    {
                        "data": "poc"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone_number"
                    },
                    {
                        "data": "whats_app"
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
                editSupplierModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteSupplierModal(id);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createSupplierForm input, #createSupplierForm select, #createSupplierForm textarea')
                .on(
                    'input change',
                    function() {
                        $(this).siblings('.invalid-feedback').text('');
                        $(this).removeClass('is-invalid');
                    });
        });

        function createSupplier() {
            var formData = new FormData($('#createSupplierForm')[0]);
            var createButton = $('#createSupplierModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('supplier.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Supplier Created Successfully!');
                    $('#createSupplierModal').modal('hide');
                    reloadDataTable();
                    $('#createSupplierModal form')[0].reset();
                },
                error: function(xhr, status, error) {
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
        $('#createSupplierForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        $('#createSupplierModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        $('#createSupplierModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        // ######Get & Update Supplier#########

        function editSupplierModal(id) {
            var showSupplier = '{{ route('supplier.show', ':id') }}';
            $.ajax({
                url: showSupplier.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editSupplier .name').val(response.name);
                    $('#editSupplier .email').val(response.email);
                    $('#editSupplier .phone_number').val(response.phone_number);
                    $('#editSupplier .poc').val(response.poc);
                    $('#editSupplier .whats_app').val(response.whats_app);
                    $('#editSupplier .address').val(response.address);
                    $('#editSupplier .alternate_phone_number').val(response.alternate_phone_number);
                    $('#editSupplier .alternate_email').val(response.alternate_email);
                    $('#editSupplier .website').val(response.website);
                    var nativeCountryValues = $('.country option').map(function() {
                        return $(this).val();
                    }).get();
                    for (let k of nativeCountryValues) {
                        if (k.includes(response.country)) {
                            $('#editSupplier .country').val(k).trigger('change');
                            fetchSupplierStates(k.split(',')[0], response.state.split(',')[0], function(
                                stateCode) {
                                if (response.state.split(',')[0]) {
                                    fetchSupplierCities(response.state.split(',')[0], k.split(',')[0],
                                        response
                                        .city);
                                }
                            });
                            break;
                        }
                    }

                    $('#editSupplier .city').val(response.city);
                    $('#editSupplierModal').modal('show');
                    $('#editSupplierModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editSupplier input, #editSupplier select, #editSupplier textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateSupplier() {
            var updateSupplier = '{{ route('supplier.update', ':id') }}';
            var id = $('#editSupplierModal').data('id');
            var formData = new FormData($('#editSupplier')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateSupplier.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Supplier Updated Successfully!');
                    $('#editSupplierModal').modal('hide');
                    reloadDataTable();
                    $('#editSupplierModal form')[0].reset();

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
        $('#editSupplierModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });

        $('#editSupplierModal').on('show.bs.modal', function() {
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').html('');
        });
        // ############# Delete Supplier Data###########
        function deleteSupplierModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteSupplierModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteSupplier(id)
            });
        });

        function deleteSupplier(id) {
            $.ajax({
                url: "{{ route('supplier.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Supplier Deleted Successfully!');
                    $('#deleteSupplierModal').modal('hide');
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
                url: '{{ route('supplierBlock.update', ['id' => ':userId']) }}'.replace(':userId',
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

        // ################### Get Dpended Country,State & City For Create Code ###################

        $('#country').change(function() {
            let countryCode = $(this).val();
            let arr = countryCode.split(',');
            $('#city').val(null).empty();
            $('#state').val(null).empty();
            $.ajax({
                url: '{{ route('fetchSupplierStates') }}',
                type: 'GET',
                data: {
                    country_code: arr[0]
                },
                success: function(data) {
                    console.log("data", data);

                    var stateSelect = $('#state');
                    stateSelect.empty();
                    stateSelect.append('<option value="">Select State</option>');
                    data.forEach(function(state) {
                        stateSelect.append('<option value="' + state.iso2 + "," + state.name +
                            '">' +
                            state.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle error here
                }
            });
        });
        $('#state').change(function() {
            let stateCode = $(this).val();
            let arr1 = stateCode.split(',');
            let countryCode = $('#country').val();
            let arr2 = countryCode.split(',');
            $.ajax({
                url: '{{ route('fetchSupplierCities') }}',
                type: 'GET',
                data: {
                    state_code: arr1[0],
                    country_code: arr2[0]
                },
                success: function(data) {
                    var citySelect = $('#city');
                    citySelect.empty();
                    citySelect.append('<option value="">Select City</option>');
                    data.forEach(function(city) {
                        citySelect.append('<option value="' + city.name + '">' +
                            city.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        // ################### Update code of country,state,city #######################
        $('.country').change(function() {
            var countryCode = $(this).val();
            if (countryCode) {
                fetchSupplierStates(countryCode.split(',')[0], null, function() {
                    $('.state').trigger('change');
                });
            }
        });
        // Trigger when state is changed
        $('.state').change(function() {
            var stateCode = $(this).val();
            var countryCode = $('.country').val();
            if (stateCode && countryCode) {
                fetchSupplierCities(stateCode.split(',')[0], countryCode.split(',')[0], null);
            }
        });

        function fetchSupplierStates(countryCode, selectedState, callback) {
            $.ajax({
                url: '{{ route('fetchSupplierStates') }}',
                type: 'GET',
                data: {
                    country_code: countryCode
                },
                success: function(data) {
                    var stateSelect = $('.state');
                    stateSelect.empty();
                    var stateCode = '';

                    // Add default "Select State" option
                    stateSelect.append('<option value="" disabled selected>Select State</option>');

                    $('.city').val(null).empty().append(
                        '<option value="" disabled selected>Select City</option>'); // Reset cities dropdown

                    data.forEach(function(stateData) {
                        var stateName = stateData.name;
                        var statecode = stateData.iso2;
                        var optionValue = statecode + ',' + stateName;
                        var option = $('<option></option>').attr('value', optionValue).text(stateName);

                        if (selectedState && (selectedState === statecode || selectedState ===
                                optionValue)) {
                            option.prop('selected', true);
                            stateCode = statecode;
                        }

                        stateSelect.append(option);
                    });

                    callback(stateCode);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching states:", xhr.responseText);
                }
            });
        }

        function fetchSupplierCities(stateCode, countryCode, selectedCity) {
            $.ajax({
                url: '{{ route('fetchSupplierCities') }}',
                type: 'GET',
                data: {
                    state_code: stateCode,
                    country_code: countryCode
                },
                success: function(data) {
                    var citySelect = $('.city');
                    citySelect.empty();
                    // Add default "Select City" option
                    citySelect.append('<option value="" disabled selected>Select City</option>');
                    data.forEach(function(cityData) {
                        var cityName = cityData.name;
                        var option = $('<option></option>').attr('value', cityName).text(cityName);
                        if (cityName === selectedCity) {
                            option.prop('selected', true);
                        }
                        citySelect.append(option);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching cities:", xhr.responseText);
                }
            });
        }
    </script>

@endsection
