@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    {{-- #############Main Content Body#################  --}}

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        @if ($data)
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-12 mb-0">
                                        <h4>Product Variants</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('product-variant.store', ['product' => $data->id]) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{-- Variants Section --}}
                                        <div id="variantFields">
                                            <!-- Initial fields -->
                                            <div class="variant-field">
                                                <div class="row col-12">
                                                    <div class="form-group col-md-12">
                                                        <label>Varient Additional Information</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][tooltip_information]"
                                                            value="{{ old('variants.0.tooltip_information') }}">
                                                    </div>
                                                </div>
                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <label>MPN</label>
                                                        <input type="text" class="form-control" name="variants[0][m_p_n]"
                                                            value="{{ old('variants.0.m_p_n') }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>SKU</label>
                                                        <input type="text" class="form-control" name="variants[0][s_k_u]"
                                                            value="{{ old('variants.0.s_k_u') }}">
                                                    </div>
                                                </div>
                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <label>Packing</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][packing]"
                                                            value="{{ old('variants.0.packing') }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Unit</label>
                                                        <select class="form-control" name="variants[0][unit]">
                                                            <option value="" disabled selected>Select Units</option>
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->name }}"
                                                                    {{ old('variants.0.unit') == $unit->name ? 'selected' : '' }}>
                                                                    {{ ucfirst($unit->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <label>Actual Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][actual_weight]"
                                                            value="{{ old('variants.0.actual_weight') }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Shipping Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][shipping_weight]"
                                                            value="{{ old('variants.0.shipping_weight') }}">
                                                    </div>
                                                </div>

                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <label>Shipping Chargeable Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][shipping_chargeable_weight]"
                                                            value="{{ old('variants.0.shipping_chargeable_weight') }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Status <span class="text-danger">*</span></label>
                                                        <select name="variants[0][status]" class="form-control">
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row col-12">
                                                    <div class="form-group col-md-4">
                                                        <label>Quantity/Packing Unit</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][quantity]"
                                                            value="{{ old('variants.0.quantity') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Total Cost/Unit ($)</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][price_per_unit]"
                                                            value="{{ old('variants.0.price_per_unit') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Selling Price/Unit ($)</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][selling_price_per_unit]"
                                                            value="{{ old('variants.0.selling_price_per_unit') }}">
                                                    </div>
                                                </div>
                                                <div class="row col-12">

                                                    <div class="form-group col-12">
                                                        <label>Description <span class="text-danger">*</span></label>
                                                        <textarea name="variants[0][description]" id="description" cols="30" rows="5" class="form-control">{{ old('variants.0.description') }}</textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Append Button & Fields -->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-success" id="addVariantBtn">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Save Button --}}
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="inactive-product">
                                <h4 class="alert-heading text-danger">Warning!</h4>
                                <p class="text-danger">This product is not active. You cannot add product variants to it.
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
        let geteditor;
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(newGetEditor => {
                geteditor = newGetEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $('form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('form')[0].reset();
                        localStorage.removeItem('productVariants');
                        setTimeout(function() {
                            window.location.href = response.redirectUrl;
                        }, 2000);

                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error("Failed to save product. Try again later.");
                    }
                }
            });
        });

        let editors = [];

        // Function to save the form data to localStorage
        function saveFormDataToLocalStorage() {
            let formData = {};
            $('.variant-field').each(function(index) {
                let variant = {};
                $(this)
                    .find(':input[name]')
                    .each(function() {
                        let name = $(this).attr('name');
                        let value = $(this).val();
                        if (name) {
                            variant[name] = value;
                        }
                    });
                formData[`variant_${index}`] = variant;
            });
            localStorage.setItem('productVariants', JSON.stringify(formData));
        }

        // Function to load form data from localStorage
        function loadFormDataFromLocalStorage() {
            let formData = JSON.parse(localStorage.getItem('productVariants'));
            if (formData) {
                Object.keys(formData).forEach((key, index) => {
                    if (index === 0) {
                        // Populate the initial field
                        populateVariantFields($('.variant-field:first'), formData[key]);
                    } else {
                        // Add and populate additional fields
                        addVariantFields();
                        populateVariantFields($('.variant-field').last(), formData[key]);
                    }
                });
            }
        }

        // Populate fields with data
        function populateVariantFields(container, data) {
            Object.keys(data).forEach(name => {
                container.find(`[name="${name}"]`).val(data[name]);
            });
        }

        function addVariantFields() {
            let variantCount = $('.variant-field').length;
            let unitsOptions = `
            <option value="" disabled selected>Select Units</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->name }}">{{ ucfirst($unit->name) }}</option>
            @endforeach
        `;
            let variantFieldHTML = `
        <div class="variant-field border border-1 mt-2">
            <div class="col-md-12 col-sm-12 col-lg-12 mt-2 mb-0 d-flex justify-content-end">
                <div class="form-group">
                    <i class="fa fa-trash btn btn-danger btn-sm removeVariantBtn"></i>
                </div>
            </div>
               <div class="row col-12">
                    <div class="form-group col-md-12">
                    <label>Varient Additional Information</label>
                    <input type="text" class="form-control"
                    name="variants[${variantCount}][tooltip_information]"
                    value="{{ old('variants.${variantCount}.tooltip_information') }}">
                    </div>
                </div>
            <div class="row col-md-12">
                <div class="form-group col-md-6">
                    <label>MPN</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][m_p_n]" value="{{ old('variants.${variantCount}.m_p_n') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][s_k_u]" value="{{ old('variants.${variantCount}.s_k_u') }}">
                </div>
            </div>
            <div class="row col-md-12">
                <div class="form-group col-md-6">
                    <label>Packing</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][packing]" value="{{ old('variants.${variantCount}.packing') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Unit</label>
                    <select class="form-control" name="variants[${variantCount}][unit]">
                        ${unitsOptions}
                    </select>
                </div>
            </div>

            <div class="row col-md-12">
                <div class="form-group col-md-6">
                    <label>Actual Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][actual_weight]" value="{{ old('variants.${variantCount}.actual_weight') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Shipping Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][shipping_weight]" value="{{ old('variants.${variantCount}.shipping_weight') }}">
                </div>
            </div>

            <div class="row col-md-12">
                <div class="form-group col-md-6">
                    <label>Shipping Chargeable Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][shipping_chargeable_weight]" value="{{ old('variants.${variantCount}.shipping_chargeable_weight') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="variants[${variantCount}][status]" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row col-12">
                <div class="form-group col-md-4">
                    <label>Quantity/Packing Unit</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][quantity]" value="{{ old('variants.${variantCount}.quantity') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Total Cost/Unit ($)</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][price_per_unit]" value="{{ old('variants.${variantCount}.price_per_unit') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Selling Price/Unit ($)</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][selling_price_per_unit]" value="{{ old('variants.${variantCount}.selling_price_per_unit') }}">
                </div>
            </div>

            <div class="form-group col-12">
                <label>Description <span class="text-danger">*</span></label>
                <textarea name="variants[${variantCount}][description]" class="form-control description" cols="30" rows="5">{{ old('variants.${variantCount}.description') }}</textarea>
            </div>
        </div>
        `;
            $('#variantFields').append(variantFieldHTML);
            if (!$(`textarea[name="variants[${variantCount}][description]"]`).data('editor')) {
                ClassicEditor
                    .create(document.querySelector(`textarea[name="variants[${variantCount}][description]"]`))
                    .then(editor => {
                        $(`textarea[name="variants[${variantCount}][description]"]`).data('editor', editor);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
        $('#addVariantBtn').click(function() {
            addVariantFields();
        });
        $(document).on('click', '.removeVariantBtn', function() {
            let variantField = $(this).closest('.variant-field');
            let editor = variantField.find('textarea').data('editor');
            if (editor) {
                editor.destroy().then(() => {
                    // Remove editor reference from the data attribute
                    variantField.find('textarea').removeData('editor');
                });
            }
            variantField.remove();
        });


        // Save on input change
        $(document).on('change', ':input', function() {
            saveFormDataToLocalStorage();
        });

        // Load saved data on page load
        $(document).ready(function() {
            loadFormDataFromLocalStorage();
            ClassicEditor.create(document.querySelector('.description'))
                .then(editor => {
                    editors.push(editor);
                })
                .catch(error => {
                    console.error(error);
                });

        });
    </script>




@endsection
