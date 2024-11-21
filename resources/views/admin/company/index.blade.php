@extends('admin.layout.app')
@section('title', 'Company')
@section('content')
    {{-- Create Company Model  --}}
    <div class="modal fade" id="createCompanyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createCompanyForm" enctype="multipart/form-data">
                        <input type="hidden" id="draft_id" name="draft_id">
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control name" id="name" name="name" required
                                        oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Website</label>
                                    <input type="text" class="form-control website" id="website" name="website"
                                        required oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="form-group col-md-6">
                                <label>Country</label>
                                <select name="country" class="form-control select2 country" id="country"
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
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <select class="form-control select2 state" id="state" name="state" style="width: 100%"
                                    required onchange="autosaveCategory()">
                                    <option value="" selected disabled>Select State</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <select class="form-control select2 city" id="city" name="city" style="width: 100%"
                                    required onchange="autosaveCategory()">
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Contact Details</label>
                                    <input type="text" class="form-control contact_detail" id="contact_detail"
                                        name="contact_detail" required oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="name">Zip</label>
                                    <input type="text" class="form-control zip" id="zip" name="zip" required
                                        oninput="autosaveCategory()">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="status">Active Status</label>
                                    <select name="status" class="form-control status" id="status"
                                        onchange="autosaveCategory()">
                                        <option value="0">In Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createCompany()">Create</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Company Modal -->
    <div class="modal fade" id="deleteCompanyModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCompanyModalLabel">Delete Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Company?</h5>
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
                                    <h4>Company</h4>
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
                                    data-target="#createCompanyModal">
                                    Create Company
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Website</th>
                                            <th>ZIP</th>
                                            <th>Visibility Status</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
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
            dataTable.ajax.url("{{ route('company.get') }}?is_draft=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('company.get') }}?is_draft=1",
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
                        "data": "country",
                        "render": function(data) {
                            if (!data || data.trim() === "") {
                                return "No data found";
                            }
                            return data.split(',')[1] ||
                                data;
                        }
                    },
                    {
                        "data": "state",
                        "render": function(data) {
                            if (!data || data.trim() === "") {
                                return "No data found";
                            }
                            return data.split(',')[1] ||
                                data;
                        }
                    },
                    {
                        "data": "city",
                        "render": function(data) {
                            if (!data || data.trim() === "") {
                                return "No data found";
                            }
                            return data.split(',')[1] ||
                                data;
                        }
                    },
                    {
                        "data": "website"
                    },
                    {
                        "data": "zip"
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
                editCompanyModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteCompanyModal(id);
            });
        });

        // ##############Create Sub admin################
        function initializeSelect2(modal) {
            modal.find('.select2').select2({
                dropdownParent: modal,
                width: '100%'
            });
        }
        $('#createCompanyModal').on('shown.bs.modal', function() {
            initializeSelect2($(this));
        });
        let autosaveTimer;

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const formData = new FormData($('#createCompanyForm')[0]);
                var formDataObject = {};
                formData.forEach(function(value, key) {
                    formDataObject[key] = value;
                });
                const draftId = $('#draft_id').val();
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('company.autosave') }}',
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

        function createCompany() {
            var formData = new FormData($('#createCompanyForm')[0]);
            var createButton = $('#createCompanyModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('company.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Company Created Successfully!');
                    $('#createCompanyModal').modal('hide');
                    reloadDataTable();
                    $('#createCompanyModal form')[0].reset();
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


        // ######Get & Update Company#########

        function editCompanyModal(id) {
            var showCompany = '{{ route('company.show', ':id') }}';
            $.ajax({
                url: showCompany.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#createCompanyForm .name').val(response.name);
                    $('#createCompanyForm .contact_detail').val(response.contact_detail);
                    $('#createCompanyForm .zip').val(response.zip);
                    $('#createCompanyForm .website').val(response.website);
                    $('#createCompanyForm .status').val(response.status);
                    var nativeCountryValues = $('.country option').map(function() {
                        return $(this).val();
                    }).get();
                    for (let k of nativeCountryValues) {
                        if (k.includes(response.country)) {
                            $('#createCompanyForm .country').val(k).trigger('change');
                            fetchCompanyStates(k.split(',')[0], response.state.split(',')[0], function(
                                stateCode) {
                                if (response.state.split(',')[0]) {
                                    fetchCompanyCities(response.state.split(',')[0], k.split(',')[0],
                                        response
                                        .city);
                                }
                            });
                            break;
                        }
                    }

                    $('#createCompanyForm .city').val(response.city);
                    $('#createCompanyModal .modal-title').text('Edit'); // Change title to Edit
                    $('#createCompanyModal .btn-success').text('Publish'); // Change button text to Update
                    $('#draft_id').val(response.id);
                    $('#createCompanyModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // ############# Delete Company Data###########
        function deleteCompanyModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteCompanyModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteCompany(id)
            });
        });

        function deleteCompany(id) {
            $.ajax({
                url: "{{ route('company.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Company Deleted Successfully!');
                    $('#deleteCompanyModal').modal('hide');
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
                url: '{{ route('companyBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
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
                url: '{{ route('fetchCompanyStates') }}',
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
                url: '{{ route('fetchCompanyCities') }}',
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
                fetchCompanyStates(countryCode.split(',')[0], null, function() {
                    $('.state').trigger('change');
                });
            }
        });
        // Trigger when state is changed
        $('.state').change(function() {
            var stateCode = $(this).val();
            var countryCode = $('.country').val();
            if (stateCode && countryCode) {
                fetchCompanyCities(stateCode.split(',')[0], countryCode.split(',')[0], null);
            }
        });

        function fetchCompanyStates(countryCode, selectedState, callback) {
            $.ajax({
                url: '{{ route('fetchCompanyStates') }}',
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

        function fetchCompanyCities(stateCode, countryCode, selectedCity) {
            $.ajax({
                url: '{{ route('fetchCompanyCities') }}',
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
