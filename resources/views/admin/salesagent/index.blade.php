@extends('admin.layout.app')
@section('title', 'Sales Managers')
@section('content')
    {{-- Create SalesAgent Model  --}}
    <div class="modal fade" id="createSalesAgentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Sales Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createSalesAgentForm" enctype="multipart/form-data">
                        <input type="hidden" id="draft_id" name="draft_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Country</label>
                                <select name="country" class="form-control select2 country" id="country"
                                    style="width: 100%">
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

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="state">State</label>
                                <select class="form-control select2 state" id="state" name="state" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Select State</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <select class="form-control select2 city" id="city" name="city" style="width: 100%"
                                    required>
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="location" name="location">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Active Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <h4>Add Accounts Information:</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_name">Bank Name</label>
                                    {{-- <input type="text" class="form-control account_name" name="account_name"> --}}
                                    <select class="form-control account_name select2" name="account_name">
                                        <option value="" disabled selected>Select Bank</option>
                                        <option value="HBL">Habib Bank Limited (HBL)</option>
                                        <option value="UBL">United Bank Limited (UBL)</option>
                                        <option value="MCB">Muslim Commercial Bank (MCB)</option>
                                        <option value="NBP">National Bank of Pakistan (NBP)</option>
                                        <option value="BankAlHabib">Bank Al Habib</option>
                                        <option value="FaysalBank">Faysal Bank</option>
                                        <option value="BankOfPunjab">Bank of Punjab (BOP)</option>
                                        <option value="StandardChartered">Standard Chartered Bank</option>
                                        <option value="AlliedBank">Allied Bank Limited (ABL)</option>
                                        <option value="BankIslami">Bank Islami Pakistan Limited</option>
                                        <option value="BankOfKhyber">The Bank of Khyber (BOK)</option>
                                        <option value="BankAlFalah">Bank Alfalah</option>
                                        <option value="FirstWomenBank">First Women Bank</option>
                                        <option value="AskariBank">Askari Bank</option>
                                        <option value="Citibank">Citibank</option>
                                        <option value="DubaiIslamicBank">Dubai Islamic Bank</option>
                                        <option value="BankOfAzadKashmir">Bank of Azad Jammu & Kashmir</option>
                                        <option value="PakBruneiInvestment">Pak Brunei Investment Company Limited</option>
                                        <option value="HabibMetropolitanBank">Habib Metropolitan Bank</option>
                                        <option value="SoneriBank">Soneri Bank</option>
                                        <option value="SindhBank">Sindh Bank</option>
                                        <option value="UMicroFinanceBank">U Microfinance Bank</option>
                                        <option value="MeezanBank">Meezan Bank</option>
                                        <option value="BankAlHabib">Bank Al Habib</option>
                                        <option value="TheBankOfPunjab">The Bank of Punjab (BOP)</option>
                                        <option value="BankOfPunjab">Bank of Punjab</option>
                                        <option value="BankIslamicPakistan">Bank Islami Pakistan Limited</option>
                                        <option value="BankOfKhyber">The Bank of Khyber (BOK)</option>
                                    </select>

                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name</label>
                                    <input type="text" class="form-control account_holder_name"
                                        name="account_holder_name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_number">IBAN Number</label>
                                    <input type="text" class="form-control account_number" name="account_number">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="submitSalesAgentForm()">Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete SalesAgent Modal -->
    <div class="modal fade" id="deleteSalesAgentModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteSalesAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSalesAgentModalLabel">Delete Sales Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Sales Manager?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSalesAgent">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Reason Modal --}}
    <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">Reason for Deactivation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reasonForm">
                        <div class="form-group">
                            <textarea class="form-control" id="reason" rows="3" name="reason" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="submitReason">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Sales Managers</h4>
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
                                    data-target="#createSalesAgentModal">
                                    Create Sales Managers
                                </a>
                                <table class="responsive table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Visibility Status</th>
                                            <th>Active & Deactivate Status</th>
                                            <th>Withdrawal History</th>
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
{{-- ############ Data Table ############## --}}
@section('js')
    <script>
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('.table').DataTable();
            dataTable.ajax.url("{{ route('salesagent.get') }}?is_draft=" + status).load();
        }

        function reloadDataTable() {
            var dataTable = $('.table').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('.table').DataTable({
                "ajax": {
                    "url": "{{ route('salesagent.get') }}?is_draft=1",
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
                        "data": "email"
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
                        "render": function(data, type, row) {
                            if (row.is_draft == 1) {
                                return '<a href="' +
                                    "{{ route('userPaymentHistory.index', ['id' => ':id']) }}"
                                    .replace(':id', row.id) +
                                    '" class="btn btn-primary mb-0 text-white"><i class="fas fa-file-invoice-dollar"></i></a>';
                            } else {
                                return '<span class="text-muted">No Withdrawal Avaiable</span>';
                            }
                        },
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
                    <a class="dropdown-item editSalesAgentBtn" href="#" data-id="${row.id}">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a class="dropdown-item deleteSalesAgentBtn text-danger" href="#" data-id="${row.id}">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </a>
                </div>
            </div>
        `;
                        }
                    }
                ]
            });


            $('.table').on('click', '.editSalesAgentBtn', function() {
                var salesAgentId = $(this).data('id');
                openEditSalesAgentModal(salesAgentId);
            });
            $('.table').on('click', '.deleteSalesAgentBtn', function() {
                var salesAgentId = $(this).data('id');
                deleteSalesAgentModal(salesAgentId);
            });
        });

        // ##############Create Sales Manager################
        function initializeSelect2(modal) {
            modal.find('.select2').select2({
                dropdownParent: modal,
                width: '100%'
            });
        }
        $('#createSalesAgentModal').on('shown.bs.modal', function() {
            initializeSelect2($(this));
        });
        let autosaveTimer;

        function autosaveCategory() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const formData = new FormData($('#createSalesAgentForm')[0]);
                var formDataObject = {};
                formData.forEach(function(value, key) {
                    formDataObject[key] = value;
                });
                const draftId = $('#draft_id').val();
                if (draftId) {
                    formData.append('draft_id', draftId);
                }
                $.ajax({
                    url: '{{ route('salesagent.autosave') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success('Data Saved Successfully!');
                        $('#draft_id').val(response.draft_id);
                    },
                    error: function(xhr) {
                        console.error('Autosave error:', xhr.responseText);
                    },
                });
            }, 1000);
        }
        $('form input, form select, form textarea').on('change input', autosaveCategory);

        function submitSalesAgentForm() {
            var formData = new FormData($('#createSalesAgentForm')[0]);
            var createButton = $('#createSalesAgentModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('salesagent.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#createSalesAgentModal').modal('hide');
                    $('#draft_id').val('');
                    reloadDataTable();
                    $('#createSalesAgentForm')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while Creating Sales Managers');
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }


        // ############# Delete SalesAgent Data###########
        function deleteSalesAgentModal(salesAgentId) {
            $('#confirmDeleteSalesAgent').data('SalesAgent-id', salesAgentId);
            $('#deleteSalesAgentModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSalesAgent').click(function() {
                var salesAgentId = $(this).data('SalesAgent-id');
                deleteSalesAgent(salesAgentId);
            });
        });

        function deleteSalesAgent(salesAgentId) {
            $.ajax({
                url: "{{ route('salesagent.delete', ['id' => ':salesAgentId']) }}".replace(':salesAgentId',
                    salesAgentId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Sales Manager Deleted Successfully!');
                    $('#deleteSalesAgentModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        // ################ Active and Inactive code ############
        $(document).ready(function() {
            var userId;
            var button;
            $('.table').on('click', '#update-status', function() {
                button = $(this);
                userId = button.data('userid');
                var currentStatus = button.text().trim().toLowerCase();
                var newStatus = currentStatus === 'active' ? '0' : '1'; // Toggle the status

                if (newStatus === '0') {
                    $('#reasonModal').modal('show');
                } else {
                    updateUserStatus(userId, newStatus, ""); // Directly update status without modal
                }
            });

            $('#submitReason').on('click', function() {
                var reason = $('#reason').val().trim();
                if (reason === "") {
                    alert("Please provide a reason for deactivation.");
                    return;
                }
                $('#reasonModal').modal('hide');
                updateUserStatus(userId, '0', reason);
            });

            function updateUserStatus(userId, newStatus, reason) {
                button.prop('disabled', true);

                $.ajax({
                    url: '{{ route('agentBlock.update', ['id' => ':userId']) }}'.replace(':userId',
                        userId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus,
                        reason: reason
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Update button text and class
                        var buttonText = newStatus === '1' ? 'Active' : 'In Active';
                        var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                        button.text(buttonText).removeClass('btn-success btn-danger').addClass(
                            buttonClass);
                        // Update status cell content
                        var statusCell = button.closest('tr').find('td:eq(6)');
                        var statusText = newStatus === '1' ? 'Active' : 'In Active';
                        var statusClass = newStatus === '1' ? 'text-success' : 'text-danger';
                        statusCell.html('<span class="' + statusClass + '">' + statusText + '</span>');
                        reloadDataTable();
                    },
                    error: function(xhr, status, error) {
                        toastr.success(response.error);
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        // Enable the button again
                        button.prop('disabled', false);
                    }
                });
            }
        });
        // ################### Get Dpended Country,State & City For Create Code ###################

        $('#country').change(function() {
            let countryCode = $(this).val();
            let arr = countryCode.split(',');
            $('#city').val(null).empty();
            $('#state').val(null).empty();
            $.ajax({
                url: '{{ route('fetchStates') }}',
                type: 'GET',
                data: {
                    country_code: arr[0]
                },
                success: function(data) {
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
            let countryCode = $('#country').val();
            if (!stateCode || !countryCode) {
                console.error("State or Country is not selected");
                return;
            }
            let arr1 = stateCode.split(',');
            let arr2 = countryCode.split(',');
            $.ajax({
                url: '{{ route('fetchCities') }}',
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
        // ################### Get Country ,State & City for Update Code ###################
        function openEditSalesAgentModal(salesAgentId) {
            var salesAgentShowRoute = '{{ route('salesagent.show', ':id') }}';
            $.ajax({
                url: salesAgentShowRoute.replace(':id', salesAgentId),
                type: 'GET',
                success: function(response) {
                    console.log("data", response);
                    $('#createSalesAgentForm #name').val(response.name);
                    $('#createSalesAgentForm #email').val(response.email);
                    $('#createSalesAgentForm #phone').val(response.phone);
                    $('#createSalesAgentForm #status').val(response.status);
                    $('#createSalesAgentForm #location').val(response.location);
                    $('#createSalesAgentModal .modal-title').text('Edit'); // Change title to Edit
                    $('#createSalesAgentModal .btn-success').text('Publish'); // Change button text to Update
                    $('#draft_id').val(response.id);
                    var imageUrl = response.image;
                    var baseUrl = 'https://ranglerzwp.xyz/macromed/';
                    var responseImage = baseUrl + imageUrl;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }
                    if (response.agent_accounts) {
                        $('#createSalesAgentForm .account_number').val(response.agent_accounts.account_number);
                        $('#createSalesAgentForm .account_name').val(response.agent_accounts.account_name);
                        $('#createSalesAgentForm .account_holder_name').val(response.agent_accounts
                            .account_holder_name);
                    } else {
                        // Handle the case where agent_accounts is null or undefined
                        $('#createSalesAgentForm .account_number').val('');
                        $('#createSalesAgentForm .account_name').val('');
                        $('#createSalesAgentForm .account_holder_name').val('');
                    }

                    var nativeCountryValues = $('.country option').map(function() {
                        return $(this).val();
                    }).get();
                    for (let k of nativeCountryValues) {
                        if (k.includes(response.country)) {
                            $('#createSalesAgentForm .country').val(k).trigger('change');

                            // Fetch states only if country exists
                            fetchStates(k.split(',')[0], response.state ? response.state.split(',')[0] :
                                null,
                                function(stateCode) {
                                    if (response.state && response.state.split(',')[0]) {
                                        fetchCities(response.state.split(',')[0], k.split(',')[0],
                                            response.city || null);
                                    } else {
                                        $('.city').val(null).empty().append(
                                            '<option value="" disabled selected>Select City</option>');
                                    }
                                });
                            break;
                        }
                    }

                    $('#createSalesAgentForm .city').val(response.city);

                    $('#createSalesAgentModal').modal('show');
                    $('#createSalesAgentModal').data('salesAgentId', salesAgentId);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        $('.country').change(function() {
            var countryCode = $(this).val();
            if (countryCode) {
                fetchStates(countryCode.split(',')[0], null, function() {
                    $('.state').trigger('change');
                });
            }
        });

        // Trigger when state is changed
        $('.state').change(function() {
            var stateCode = $(this).val();
            var countryCode = $('.country').val();
            if (stateCode && countryCode) {
                fetchCities(stateCode.split(',')[0], countryCode.split(',')[0], null);
            }
        });

        function fetchStates(countryCode, selectedState, callback) {
            $.ajax({
                url: '{{ route('fetchStates') }}',
                type: 'GET',
                data: {
                    country_code: countryCode
                },
                success: function(data) {
                    var stateSelect = $('.state');
                    stateSelect.empty();
                    stateSelect.append('<option value="" disabled selected>Select State</option>');
                    $('.city').val(null).empty().append(
                        '<option value="" disabled selected>Select City</option>');

                    if (data.length === 0) {
                        callback(null); // No states available
                        return;
                    }

                    data.forEach(function(stateData) {
                        var optionValue = `${stateData.iso2},${stateData.name}`;
                        var option = $('<option></option>').attr('value', optionValue).text(stateData
                            .name);
                        if (selectedState && selectedState === stateData.iso2) {
                            option.prop('selected', true);
                        }
                        stateSelect.append(option);
                    });

                    callback(selectedState);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching states:", xhr.responseText);
                }
            });
        }

        function fetchCities(stateCode, countryCode, selectedCity) {
            $.ajax({
                url: '{{ route('fetchCities') }}',
                type: 'GET',
                data: {
                    state_code: stateCode,
                    country_code: countryCode
                },
                success: function(data) {
                    var citySelect = $('.city');
                    citySelect.empty();
                    citySelect.append('<option value="" disabled selected>Select City</option>');

                    if (data.length === 0) return;

                    data.forEach(function(cityData) {
                        var option = $('<option></option>').attr('value', cityData.name).text(cityData
                            .name);
                        if (selectedCity && cityData.name === selectedCity) {
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
