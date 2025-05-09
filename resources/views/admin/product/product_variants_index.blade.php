@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    <!-- Delete Subadmin Modal -->
    <div class="modal fade" id="deleteSubadminModal" tabindex="-1" role="dialog" aria-labelledby="deleteSubadminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Product Variant </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this product variant ?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Variants Modal -->
    <div class="modal fade" id="editVariantsModal" tabindex="-1" role="dialog" aria-labelledby="editVariantsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariantsModalLabel">Edit Variants</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editVariants" enctype="multipart/form-data">
                        <div class="row col-12 col-md-12">
                            <div class="form-group col-md-12">
                                <label>Varient Additional Information</label>
                                <input type="text" class="form-control tooltip_information" name="tooltip_information">
                            </div>
                        </div>
                        <div class="row col-6 d-flex col-md-6">
                            <!-- Input to Upload New Image -->
                            <div class="flex-grow-1">
                                <div class="form-group col-md-12">
                                    <label>Tool Tip Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <small text-muted>(Image should be of size 2MB)</small>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Display Existing Image -->

                                <div class="ms-3">
                                    <img src=""
                                         alt="image"
                                         class="image-preview"
                                         style="width: 80px; height: 80px; margin-left:20px;border: 1px solid #ddd;">
                                </div>

                        </div>
                        <div class="row col-12">
                            <div class="form-group col-md-4">
                                <label>MPN</label>
                                <input type="text" class="form-control m_p_n" name="m_p_n">
                            </div>
                            <div class="form-group col-md-4">
                                <label>SKU</label>
                                <input type="text" class="form-control s_k_u" name="s_k_u">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Packing</label>
                                {{-- <input type="text" class="form-control packing" name="packing"> --}}
                                <select class="form-control packing" name="packing">
                                    <option value="" disabled selected>Select Packings</option>
                                    @foreach ($packinges as $data)
                                        <option value="{{ $data->name }}">
                                            {{ ucfirst($data->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-4">
                                <label>Unit</label>
                                <select class="form-control unit" name="unit">
                                    <option value="" disabled selected>Select Units</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->name }}">
                                            {{ ucfirst($unit->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Quantity</label>
                                <input type="text" class="form-control quantity" name="quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Remianing Quantity</label>
                                <input type="text" class="form-control remaining_quantity" name="remaining_quantity"
                                    disabled>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                <label>Actual Price/Unit</label>
                                <input type="text" class="form-control price_per_unit" name="price_per_unit">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Selling Price/Unit</label>
                                <input type="text" class="form-control selling_price_per_unit"
                                    name="selling_price_per_unit">
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                <label>Actual Weight</label>
                                <input type="text" class="form-control actual_weight" name="actual_weight">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Volumetric Weight</label>
                                <input type="text" class="form-control volumetric_weight" name="volumetric_weight">
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-6">
                                <label>Shipping Chargeable Weight</label>
                                <input type="text" class="form-control shipping_chargeable_weight"
                                    name="shipping_chargeable_weight">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" cols="30" rows="5" class="form-control description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center mb-0">
                    <button type="button" class="btn btn-success" onclick="updateVariants()">Update</button>
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
                        <a class="btn btn-primary mb-3" href="{{ route('product.index') }}">Back</a>
                        @if ($product->status == '1')
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-12">
                                        <h4>Product Varaints</h4>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    {{-- <div class="form-group col-sm-3 mb-3 px-0">
                                        <label for="periodSelect">Visibility Status</label>
                                        <select id="periodSelect" class="form-control" onchange="loadData()">
                                            <option value="1" selected><span class="text-danger">Published Data</span>
                                            </option>
                                            <option value="0">Draft Data</option>
                                        </select>
                                    </div> --}}
                                    <table class="responsive table table-striped table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Visibility Status</th>
                                                <th>MPN</th>
                                                <th>SKU</th>
                                                <th>Packing</th>
                                                <th>Unit</th>
                                                <th>Total Quantity</th>
                                                <th>Remaining Quantity</th>
                                                <th>Price per Unit</th>
                                                <th>Selling Price per Unit</th>
                                                <th>Actual Weight</th>
                                                <th>Shipping Weight</th>
                                                <th>Shipping Chargeable Weight</th>
                                                <th>Tool Tip Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                {{-- <th>Description</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="inactive-product">
                                <h4 class="alert-heading text-danger">Warning!</h4>
                                <p class="text-danger">This product is not active. You cannot view product variants to it.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('js')
    <script>
        function loadData() {
            var status = $('#periodSelect').val(); // Get the selected status
            var dataTable = $('#example').DataTable();
            dataTable.ajax.url("{{ route('product.variants', ['id' => $id]) }}?is_draft=" + status).load();
        }
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {

            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('product.variants', ['id' => $id]) }}",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    },
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1; // Display row index
                        }
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
                        "data": "m_p_n"
                    },
                    {
                        "data": "s_k_u"
                    },
                    {
                        "data": "packing"
                    },
                    {
                        "data": "unit"
                    },
                    {
                        "data": "quantity"
                    },
                    {
                        "data": "remaining_quantity"
                    },
                    {
                        "data": "price_per_unit",
                        "render": function(data, type, row) {
                            return '$' + data;
                        }
                    },
                    {
                        "data": "selling_price_per_unit",
                        "render": function(data, type, row) {
                            return '$' + data;
                        }
                    },
                    {
                        "data": "actual_weight"
                    },
                    {
                        "data": "volumetric_weight"
                    },
                    {
                        "data": "shipping_chargeable_weight"
                    },
                    {
    "data": "image",
    "render": function(data, type, row) {
        return '<img src="{{ asset("/public/admin/assets/images/products") }}/' + data +
               '" alt="Tool_Tip Image" style="width: 50px; height: 50px;">';
    }
}
,
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
                            return '<button class="btn btn-success mb-1 mr-1 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-0 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';

                        }
                    }
                    // { "data": "description" }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editVariantsModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });
        });


        // ######Get & Update Variants#########

        function editVariantsModal(id) {
            var showVariants = '{{ route('variants.show', ':id') }}';
            $.ajax({
                url: showVariants.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editVariants .tooltip_information').val(response.tooltip_information);
                    $('#editVariants .m_p_n').val(response.m_p_n);
                    $('#editVariants .s_k_u').val(response.s_k_u);
                    $('#editVariants .packing').val(response.packing);
                    $('#editVariants .unit').val(response.unit);
                    $('#editVariants .quantity').val(response.quantity);
                    $('#editVariants .price_per_unit').val(response.price_per_unit);
                    $('#editVariants .selling_price_per_unit').val(response.selling_price_per_unit);
                    $('#editVariants .actual_weight').val(response.actual_weight);
                    $('#editVariants .volumetric_weight').val(response.volumetric_weight);
                    $('#editVariants .remaining_quantity').val(response.remaining_quantity);
                    $('#editVariants .shipping_chargeable_weight').val(response
                        .shipping_chargeable_weight);
                    $('#editVariants .status').val(response.status);
                    if (response.description !== null) {
                        geteditor.setData(response.description);
                    } else {
                        geteditor.setData(''); // Or set it to an empty string
                    }
                    if (response.image) {
                let imagePath = '{{ asset("/public/admin/assets/images/products") }}/' + response.image;
                $('#editVariants .image-preview').attr('src', imagePath).show();
            } else {
                $('#editVariants .image-preview').hide();
            }
                    $('#editVariantsModal').modal('show');
                    $('#editVariantsModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editVariants input, #editVariants select, #editVariants textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateVariants() {
            var updateVariants = '{{ route('variants.update', ':id') }}';
            var id = $('#editVariantsModal').data('id');
            var form = document.getElementById("editVariants");
            var tooltip_information = form["tooltip_information"].value;
            var m_p_n = form["m_p_n"].value;
            var s_k_u = form["s_k_u"].value;
            var packing = form["packing"].value;
            var unit = form["unit"].value;
            var quantity = form["quantity"].value;
            var price_per_unit = form["price_per_unit"].value;
            var selling_price_per_unit = form["selling_price_per_unit"].value;
            var actual_weight = form["actual_weight"].value;
            var volumetric_weight = form["volumetric_weight"].value;
            var shipping_chargeable_weight = form["shipping_chargeable_weight"].value;
            var status = form["status"].value;
            var description = geteditor.getData();
            // ########### Form Data ###########
            var formData = new FormData();
            formData.append('tooltip_information', tooltip_information);
            formData.append('m_p_n', m_p_n);
            formData.append('s_k_u', s_k_u);
            formData.append('packing', packing);
            formData.append('unit', unit);
            formData.append('quantity', quantity);
            formData.append('price_per_unit', price_per_unit);
            formData.append('selling_price_per_unit', selling_price_per_unit);
            formData.append('actual_weight', actual_weight);
            formData.append('shipping_chargeable_weight', shipping_chargeable_weight);
            formData.append('volumetric_weight', volumetric_weight);
            formData.append('status', status);
            formData.append('description', description);

            var imageFile = form["image"].files[0];
if (imageFile) {
    formData.append('image', imageFile);
}
            $.ajax({
                url: updateVariants.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Variants Updated Successfully!');
                    $('#editVariantsModal').modal('hide');
                    reloadDataTable();
                    $('#editVariantsModal form')[0].reset();

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred while updating the product.');
                    }
                }
            });
        }
        // Update status event listener
        $('#example').on('click', '#update-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentStatus = button.text().trim().toLowerCase();
            var newStatus = currentStatus === 'active' ? '1' : '0';
            button.prop('disabled', true);
            $.ajax({
                url: '{{ route('variantsBlock.update', ['id' => ':userId']) }}'.replace(
                    ':userId', userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus === '1' ? 'Active' : 'Inactive';
                    var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(
                        buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
                    statusCell.html('<span class="' + statusClass + '">' + statusText +
                        '</span>');
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

        // Delete Subadmin function
        function deleteSubadminModal(subadminId) {
            $('#confirmDeleteSubadmin').data('subadmin-id', subadminId);
            $('#deleteSubadminModal').modal('show');
        }

        $('#confirmDeleteSubadmin').click(function() {
            var subadminId = $(this).data('subadmin-id');
            deleteSubadmin(subadminId);
        });

        function deleteSubadmin(subadminId) {
            $.ajax({
                url: "{{ route('variant.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                    subadminId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Product Variant Deleted Successfully!');
                    $('#deleteSubadminModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        let geteditor;
        ClassicEditor
            .create(document.querySelector('.description'))
            .then(newGetEditor => {
                geteditor = newGetEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
