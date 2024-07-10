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
                                                    <div class="form-group col-md-4">
                                                        <label>SKU</label>
                                                        <input type="text" class="form-control" name="variants[0][s_k_u]"
                                                            value="{{ old('variants.0.s_k_u') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Packing</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][packing]"
                                                            value="{{ old('variants.0.packing') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Unit</label>
                                                        <input type="text" class="form-control" name="variants[0][unit]"
                                                            value="{{ old('variants.0.unit') }}">
                                                    </div>
                                                </div>
                                                <div class="row col-12">
                                                    <div class="form-group col-md-4">
                                                        <label>Quantity</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][quantity]"
                                                            value="{{ old('variants.0.quantity') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Actual Price/Unit</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][price_per_unit]"
                                                            value="{{ old('variants.0.price_per_unit') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Selling Price/Unit</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][selling_price_per_unit]"
                                                            value="{{ old('variants.0.selling_price_per_unit') }}">
                                                    </div>
                                                </div>
                                                <div class="row col-12">
                                                    <div class="form-group col-md-4">
                                                        <label>Actual Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][actual_weight]"
                                                            value="{{ old('variants.0.actual_weight') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Shipping Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][shipping_weight]"
                                                            value="{{ old('variants.0.shipping_weight') }}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Shipping Chargeable Weight</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[0][shipping_chargeable_weight]"
                                                            value="{{ old('variants.0.shipping_chargeable_weight') }}">
                                                    </div>
                                                    <div class="form-group col-12">
                                                        <label>Status <span class="text-danger">*</span></label>
                                                        <select name="variants[0][status]" class="form-control">
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <textarea name="variants[0][description]" id="description" cols="30" rows="5" class="form-control ">{{ old('variants.0.description') }}</textarea>
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
                            <div class="alert alert-danger alert-dismissible fade show text-center mt-5" role="alert">
                                <h4 class="alert-heading text-white">Warning!</h4>
                                <p class="text-white">This product is not active. You cannot add product variants to it.</p>
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
        // Initialize Classic Editor for all long description fields
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
        let editors = [];

        function addVariantFields() {
            let variantCount = $('.variant-field').length;
            let variantFieldHTML = `
        <div class="variant-field border border-1 mt-2">
             <div class="col-md-12 col-sm-12 col-lg-12 mt-2 mb-0 d-flex justify-content-end">
    <div class="form-group">
        <i class="fa fa-trash btn btn-danger btn-sm removeVariantBtn"></i>
    </div>
</div>
            <div class="row col-12">
                <div class="form-group col-md-4">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][s_k_u]"
                        value="{{ old('variants.${variantCount}.s_k_u') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Packing</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][packing]"
                        value="{{ old('variants.${variantCount}.packing') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Unit</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][unit]"
                        value="{{ old('variants.${variantCount}.unit') }}">
                </div>
            </div>
            <div class="row col-12">
                <div class="form-group col-md-4">
                    <label>Quantity</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][quantity]"
                        value="{{ old('variants.${variantCount}.quantity') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Actual Price/Unit</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][price_per_unit]"
                        value="{{ old('variants.${variantCount}.price_per_unit') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Selling Price/Unit</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][selling_price_per_unit]"
                        value="{{ old('variants.${variantCount}.selling_price_per_unit') }}">
                </div>
            </div>
            <div class="row col-12">
                <div class="form-group col-md-4">
                    <label>Actual Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][actual_weight]"
                        value="{{ old('variants.${variantCount}.actual_weight') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Shipping Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][shipping_weight]"
                        value="{{ old('variants.${variantCount}.shipping_weight') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Shipping Chargeable Weight</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][shipping_chargeable_weight]"
                        value="{{ old('variants.${variantCount}.shipping_chargeable_weight') }}">
                </div>
                <div class="form-group col-12">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="variants[${variantCount}][status]" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-12">
                <label>description <span class="text-danger">*</span></label>
                <textarea name="variants[${variantCount}][description]" class="form-control description" cols="30" rows="5">{{ old('variants.${variantCount}.description') }}</textarea>
            </div>
        </div>
        `;
            $('#variantFields').append(variantFieldHTML);
            ClassicEditor
                .create(document.querySelector(`textarea[name="variants[${variantCount}][description]"]`))
                .then(editor => {
                    editors.push(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Remove variant field
        $(document).on('click', '.removeVariantBtn', function() {
            let index = $('.removeVariantBtn').index(this);
            editors[index].destroy().then(() => {
                editors.splice(index, 1);
                $(this).closest('.variant-field').remove();
            });
        });

        // Event listener for Add Variant button
        $('#addVariantBtn').click(function() {
            addVariantFields();
        });

        // Initialize the first Classic Editor
        ClassicEditor
            .create(document.querySelector('.description'))
            .then(editor => {
                editors.push(editor);
            })
            .catch(error => {
                console.error(error);
            });

        //#### Torster Message##########
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>



@endsection
