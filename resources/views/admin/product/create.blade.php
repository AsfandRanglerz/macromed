@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Create Product</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group col-12">
                                        <label>Thumbnail Image Preview</label>
                                        <div>
                                            <img id="preview-img" class="admin-img"
                                                src="{{ asset('public/admin/assets/images/preview.png') }}"
                                                style="width: 15%" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Thumnail Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="thumb_image"
                                            onchange="previewThumnailImage(event)">
                                    </div>

                                    <div class="form-group col-12">
                                        <label>Banner Image<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="banner_image">
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-4">
                                            <label>Short Name<span class="text-danger">*</span></label>
                                            <input type="text" id="short_name" class="form-control" name="short_name"
                                                value="{{ old('short_name') }}">
                                        </div>
                                        <div class="form-group col-4">
                                            <label>Name<span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group col-4">
                                            <label>Slug<span class="text-danger">*</span></label>
                                            <input type="text" id="slug" class="form-control" name="slug"
                                                value="{{ old('slug') }}">
                                        </div>
                                    </div>

                                    <div class="row col-12">
                                        <div class="form-group col-4">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select name="category[]" class="form-control select2" id="category" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>Sub Category</label>
                                            <select name="sub_category[]" class="form-control select2" id="sub_category"
                                                multiple disabled>
                                                <option value="">Select Sub Category</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-4">
                                            <label>Brand <span class="text-danger">*</span></label>
                                            <select name="brand" class="form-control select2" id="brand" multiple>
                                                @foreach ($brands as $brand)
                                                    <option {{ old('brand') == $brand->id ? 'selected' : '' }}
                                                        value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- ########### Certification & Models & Company ############ --}}
                                    <div class="row col-12">
                                        <div class="form-group col-4">
                                            <label>Certications <span class="text-danger">*</span></label>
                                            <select name="certification" class="form-control select2" id="certification"
                                                multiple>
                                                @foreach ($certifications as $certification)
                                                    <option value="{{ $certification->id }}">
                                                        {{ $certification->name }}</option>
                                                @endforeach

                                        </div>
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Company</label>
                                        <select name="company" class="form-control" id="company">
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">
                                                    {{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Models <span class="text-danger">*</span></label>
                                        <select name="model" class="form-control" id="models">
                                            <option value="" disabled selected>Select Models</option>
                                            @foreach ($models as $model)
                                                <option {{ old('brand') == $model->id ? 'selected' : '' }}
                                                    value="{{ $model->id }}">{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            {{-- ########### Actual Price & Selling Price & Country ############ --}}
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>Country</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso2 . ',' . $country->name }}">
                                                {{ $country->name }}</option>
                                        @endforeach
                                        @if ($countries == null)
                                            <div class="internet-error text-danger">Please Check Your Internet
                                                Connection
                                            </div>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label>Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity"
                                        value="{{ old('quantity') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Video Link</label>
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            {{-- ########### Stock Quantity & Video Link & Country ############ --}}
                            <div class="row col-12">
                                <div class="form-group col-6">
                                    <label>Product Commission</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-6">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                <label>Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                            </div>

                            <div class="form-group col-12">
                                <label>Long Description <span class="text-danger">*</span></label>
                                <textarea name="long_description" id="" cols="30" rows="10" class="long_description">{{ old('long_description') }}</textarea>
                            </div>
                            {{-- ############ Variants Sections ############## --}}
                            <div class="form-group col-12">
                                <h4>Variants <span class="text-danger">*</span></h4>
                            </div>
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Packing</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Actuall Price/Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Selling Price/Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>Actuall Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Shipping Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Shipping Chargeable Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="long_description" id="" cols="30" rows="10" class="long_description">{{ old('long_description') }}</textarea>
                            </div>
                            {{-- Append Button & Feilds --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-success" id="addFieldsBtn">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="additionalFields">
                            </div>
                            {{-- ############ Variants Sections ############## --}}

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success">Save</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

@endsection

@section('js')
    <script>
        // Image perview code
        function previewThumnailImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };
        // Classic Editor Code
        let editors = [];

        document.querySelectorAll('.long_description').forEach((textarea, index) => {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    editors[index] = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        });

        // Show sub Categories against Category
        $(document).ready(function() {
            $('#category').change(function() {
                var selectedCategories = $(this).val();
                if (selectedCategories.length > 0) {
                    $.ajax({
                        url: '{{ route('category.subCategories') }}',
                        type: 'GET',
                        data: {
                            category_ids: selectedCategories
                        },
                        success: function(response) {
                            $('#sub_category').empty();
                            if (response.length > 0) {
                                response.forEach(function(subCategory) {
                                    $('#sub_category').append('<option value="' +
                                        subCategory.id + '">' + subCategory.name +
                                        '</option>');
                                });
                                $('#sub_category').prop('disabled', false);
                            } else {
                                $('#sub_category').append(
                                    '<option value="">No Sub Category Available</option>');
                                $('#sub_category').prop('disabled', true);
                            }
                        }
                    });
                } else {
                    $('#sub_category').empty();
                    $('#sub_category').append('<option value="">Select Sub Category</option>');
                    $('#sub_category').prop('disabled', true);
                }
            });
        });
        // Appended fields code
        function addAdditionalFields() {
            var additionalFieldsHTML = `
            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Packing</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Actuall Price/Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Selling Price/Unit</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="form-group col-4">
                                    <label>Actuall Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Shipping Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                                <div class="form-group col-4">
                                    <label>Shipping Chargeable Weight</label>
                                    <input type="text" class="form-control" name="product_commission"
                                        value="{{ old('video_link') }}">
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="long_description" id="" cols="30" rows="10" class="long_description">{{ old('long_description') }}</textarea>
                            </div>
            <div class="col-md-3 col-sm-3 col-lg-3">
                <div class="form-group" style="margin-top: 29px">
                    <button type="button" class="btn btn-danger removeField" ><i class="fa fa-trash"></i></button>
                </div>
            </div>
    `;
            $('#additionalFields').append(additionalFieldsHTML);
        }
        $(document).on('click', '.removeField', function() {
            $(this).closest('.row').remove();
        });
        // Event listener for the Add button
        $('#addFieldsBtn').click(function() {
            addAdditionalFields();
        });
    </script>

@endsection
