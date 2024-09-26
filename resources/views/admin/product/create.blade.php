@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-md-12 col-md-12 col-lg-12">
                        <a class="btn btn-primary mb-3" href="{{ route('product.index') }}">Back</a>
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <h4>Create Product</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group col-md-12">
                                        <label>Thumbnail Image Preview</label>
                                        <div>
                                            <img id="preview-img" class="admin-img"
                                                src="{{asset('public/admin/assets/images/preview.png') }}"
                                                style="width: 15%" alt="Thumbnail Preview">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Thumbnail Image</label>
                                        <input type="file" class="form-control-file" name="thumbnail_image"
                                            value="{{ old('thumbnail_image') }}" onchange="previewThumbnailImage(event)">
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Product Short Name<span class="text-danger">*</span></label>
                                            <input type="text" id="short_name" class="form-control" name="short_name"
                                                value="{{ old('short_name') }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Product Name<span class="text-danger">*</span></label>
                                            <input type="text" id="product_name" class="form-control name"
                                                name="product_name" value="{{ old('product_name') }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Slug<span class="text-danger">*</span></label>
                                            <input type="text" id="slug" class="form-control slug" name="slug"
                                                value="{{ old('slug') }}">
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select name="category_id[]" class="form-control select2" id="category"
                                                multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, old('category_id', [])) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Sub Category</label>
                                            <select name="sub_category_id[]" class="form-control select2" id="sub_category"
                                                multiple>
                                                <option value="">Select Sub Category</option>
                                                @if (old('sub_category_id'))
                                                    @foreach ($subCategories as $subCategory)
                                                        <!-- Assuming $subCategories is passed -->
                                                        <option value="{{ $subCategory->id }}"
                                                            {{ in_array($subCategory->id, old('sub_category_id', [])) ? 'selected' : '' }}>
                                                            {{ $subCategory->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Brand <span class="text-danger">*</span></label>
                                            <select name="brand_id[]" class="form-control select2" id="brand" multiple>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ in_array($brand->id, old('brand_id', [])) ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <!-- Certifications Field -->
                                        <div class="form-group col-md-4">
                                            <label>Certifications <span class="text-danger">*</span></label>
                                            <select name="certification_id[]" class="form-control select2"
                                                id="certification" multiple>
                                                @foreach ($certifications as $certification)
                                                    <option value="{{ $certification->id }}"
                                                        {{ in_array($certification->id, old('certification_id', [])) ? 'selected' : '' }}>
                                                        {{ $certification->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Company Field -->
                                        <div class="form-group col-md-4">
                                            <label>Company</label>
                                            <select name="company" class="form-control select2" id="company">
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->name }}"
                                                        {{ old('company') == $company->name ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Models Field -->
                                        {{-- <div class="form-group col-md-4">
                                            <label>Models <span class="text-danger">*</span></label>
                                            <select name="models" class="form-control select2" id="models">
                                                <option value="" disabled selected>Select Models</option>
                                                @foreach ($models as $model)
                                                    <option value="{{ $model->name }}"
                                                        {{ old('models') == $model->name ? 'selected' : '' }}>
                                                        {{ $model->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="form-group col-md-4">
                                            <label>Product HTS Code<span class="text-danger">*</span></label>
                                            <input type="text" id="product_hts" class="form-control product_hts"
                                                name="product_hts" value="{{ old('product_hts') }}">
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Country</label>
                                            <select name="country" class="form-control select2" id="country">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->name }}"
                                                        {{ old('country') == $country->name ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($countries == null)
                                                <div class="internet-error text-danger">No Internet Connection Found!</div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Product Commission <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="product_commission"
                                                    value="{{ old('product_commission') }}">
                                                <span class="input-group-addon"
                                                    style="
                                                border: 2px solid #ced4da;
                                                border-left: 0;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                width: 3rem;
                                                font-weight: bold;
                                            ">%</span>
                                            </div>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Video Link</label>
                                            <input type="text" class="form-control" name="video_link"
                                                value="{{ old('video_link') }}">
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-md-4">
                                            <label>Number Of Use <span class="text-danger">*</span></label>
                                            <select name="product_use_status" class="form-control select2">
                                                <option value="" disabled selected>Select Number Of Use</option>
                                                @foreach ($numberOfUses as $numberOfUse)
                                                    <option value="{{ $numberOfUse->name }}"
                                                        {{ old('product_use_status') == $numberOfUse->name ? 'selected' : '' }}>
                                                        {{ $numberOfUse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Sterilizations <span class="text-danger">*</span></label>
                                            <select name="sterilizations" class="form-control select2"
                                                id="sterilizations">
                                                <option value="" disabled selected>Select Sterilizations</option>
                                                @foreach ($sterilizations as $sterilization)
                                                    <option value="{{ $sterilization->name }}"
                                                        {{ old('sterilizations') == $sterilization->name ? 'selected' : '' }}>
                                                        {{ $sterilization->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Buyer Type <span class="text-danger">*</span></label>
                                            <select name="buyer_type" class="form-control select2">
                                                <option value="" disabled
                                                    {{ old('buyer_type') == '' ? 'selected' : '' }}>Select Buyer Type
                                                </option>
                                                <option value="Individual Customer"
                                                    {{ old('buyer_type') == ' Individual Customer' ? 'selected' : '' }}>
                                                    Individual Customer
                                                </option>
                                                <option value="Clinic"
                                                    {{ old('buyer_type') == 'Clinic' ? 'selected' : '' }}>Clinic
                                                </option>
                                                <option value="Private Hospital"
                                                    {{ old('buyer_type') == ' Private Hospital' ? 'selected' : '' }}>
                                                    Private Hospital
                                                </option>
                                                <option value="Govt. Hospital"
                                                    {{ old('buyer_type') == 'Govt. Hospital' ? 'selected' : '' }}>Govt.
                                                    Hospital
                                                </option>
                                                <option value="Reseller"
                                                    {{ old('buyer_type') == 'Reseller' ? 'selected' : '' }}>Reseller
                                                </option>
                                                <option value="Distributor"
                                                    {{ old('buyer_type') == 'Distributor' ? 'selected' : '' }}>Distributor
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <!-- Product Class Field -->
                                        <div class="form-group col-md-4">
                                            <label>Product Class <span class="text-danger">*</span></label>
                                            <select name="product_class" class="form-control select2">
                                                <option value="" disabled
                                                    {{ old('product_class') == '' ? 'selected' : '' }}>Select Product Class
                                                </option>
                                                <option value="Class A-1"
                                                    {{ old('product_class') == 'Class A-1' ? 'selected' : '' }}>Class A-1
                                                </option>
                                                <option value="Class B-2"
                                                    {{ old('product_class') == 'Class B-2' ? 'selected' : '' }}>Class B-2
                                                </option>
                                                <option value="Class C-3"
                                                    {{ old('product_class') == 'Class C-3' ? 'selected' : '' }}>Class C-3
                                                </option>
                                                <option value="Class D-4"
                                                    {{ old('product_class') == 'Class D-4' ? 'selected' : '' }}>Class D-4
                                                </option>
                                                <option value="Class E-5"
                                                    {{ old('product_class') == 'Class E-5' ? 'selected' : '' }}>Class E-5
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Main Material Field -->
                                        <div class="form-group col-md-4">
                                            <label>Main Material <span class="text-danger">*</span></label>
                                            <select name="material_id[]" class="form-control select2" multiple>
                                                @foreach ($mianMaterials as $mainMaterial)
                                                    <option value="{{ $mainMaterial->id }}"
                                                        {{ collect(old('material_id'))->contains($mainMaterial->id) ? 'selected' : '' }}>
                                                        {{ $mainMaterial->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Supplier Name Field -->
                                        <div class="form-group col-md-4">
                                            <label>Supplier Name <span class="text-danger">*</span></label>
                                            <select id="supplier_name" name="supplier_name_display"
                                                class="form-control select2">
                                                <!-- Placeholder option -->
                                                <option value="" disabled>Select Supplier Name</option>
                                                @if (old('supplier_name_display'))
                                                    <option value="{{ old('supplier_name_display') }}" selected>
                                                        {{ old('supplier_name') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <!-- Supplier Id Field -->
                                        <div class="form-group col-md-4">
                                            <label>Supplier Id <span class="text-danger">*</span></label>
                                            <select id="supplier_id" name="supplier_id_display"
                                                class="form-control select2" disabled>
                                                <!-- Placeholder option -->
                                                <option value="" disabled>Select Supplier ID</option>
                                                @if (old('supplier_id_display'))
                                                    <option value="{{ old('supplier_id_display') }}" selected>
                                                        {{ old('supplier_id') }}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Hidden fields for actual values -->
                                        <input type="hidden" id="supplier_name_hidden" name="supplier_name"
                                            value="{{ old('supplier_name') }}">
                                        <input type="hidden" id="supplier_id_hidden" name="supplier_id"
                                            value="{{ old('supplier_id') }}">

                                        <!-- Supplier Delivery Time Field -->
                                        <div class="form-group col-md-4">
                                            <label>Supplier Delivery Period <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="supplier_delivery_time"
                                                value="{{ old('supplier_delivery_time') }}">
                                        </div>

                                        <!-- Delivery Period Field -->
                                        <div class="form-group col-md-4">
                                            <label>Delivery Period <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="delivery_period"
                                                value="{{ old('delivery_period') }}">
                                        </div>
                                    </div>

                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Short Description <span class="text-danger">*</span></label>
                                            <textarea name="short_description" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Long Description <span class="text-danger">*</span></label>
                                            <textarea name="long_description" cols="20" rows="50" class="long_description">{{ old('long_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-6">
                                            <label>Shelf Life / Expiry Period<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="self_life"
                                                value="{{ old('self_life') }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    {{-- Tabs  --}}
                                    <div class="row col-md-12">
                                        <h4 class="col-md-12">Tabs Content:</h4>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 1 Heading</label>
                                            <input type="text" class="form-control" name="tab_1_heading"
                                                value="{{ old('tab_1_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 1 Content</label>
                                            <textarea name="tab_1_text" cols="20" rows="50" class="long_description">{{ old('tab_1_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 2 Heading</label>
                                            <input type="text" class="form-control" name="tab_2_heading"
                                                value="{{ old('tab_2_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 2 Content</label>
                                            <textarea name="tab_2_text" cols="20" rows="50" class="long_description">{{ old('tab_2_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 3 Heading</label>
                                            <input type="text" class="form-control" name="tab_3_heading"
                                                value="{{ old('tab_3_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 3 Content</label>
                                            <textarea name="tab_3_text" cols="20" rows="50" class="long_description">{{ old('tab_3_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 4 Heading</label>
                                            <input type="text" class="form-control" name="tab_4_heading"
                                                value="{{ old('tab_4_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 4 Content</label>
                                            <textarea name="tab_4_text" cols="20" rows="50" class="long_description">{{ old('tab_4_text') }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Tabs --}}
                                    <hr>
                                    <div class="row col-md-12">
                                        <h4 class="col-md-12">Taxes:</h4>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-6">
                                            <label>Federal Tax<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="federal_tax"
                                                value="{{ old('federal_tax') }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Provincial Tax <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="provincial_tax"
                                                value="{{ old('provincial_tax') }}">
                                        </div>
                                    </div>

                                    <div class="tax-fields">
                                        <div id="taxFields" class="row col-md-12">
                                            {{-- City List  --}}
                                            <div class="form-group col-md-6">
                                                <label>Tax/City<span class="text-danger">*</span></label>
                                                <select name="taxes[0][tax_per_city]" class="form-control select2">
                                                    <option value="" disabled
                                                        {{ old('taxes.0.tax_per_city') == null ? 'selected' : '' }}>Select
                                                        City</option>
                                                    @foreach ([
            'Karachi',
            'Lahore',
            'Faisalabad',
            'Rawalpindi',
            'Multan',
            'Hyderabad',
            'Gujranwala',
            'Peshawar',
            'Quetta',
            'Islamabad',
            'Sargodha',
            'Sialkot',
            'Bahawalpur',
            'Sukkur',
            'Larkana',
            'Sheikhupura',
            'Mardan',
            'Gujrat',
            'Rahim Yar Khan',
            'Kasur',
            'Okara',
            'Sahiwal',
            'Wah Cantonment',
            'Dera Ghazi Khan',
            'Mingora',
            'Mirpur Khas',
            'Chiniot',
            'Nawabshah',
            'Kamoke',
            'Burewala',
            'Jhelum',
            'Sadiqabad',
            'Khanewal',
            'Hafizabad',
            'Kohat',
            'Jacobabad',
            'Shikarpur',
            'Muzaffargarh',
            'Abottabad',
            'Muridke',
            'Jhang',
            'Daska',
            'Mandi Bahauddin',
            'Khuzdar',
            'Pakpattan',
            'Tando Allahyar',
            'Vehari',
            'Gojra',
            'Mandi Bahauddin',
            'Turbat',
            'Dadu',
            'Bahawalnagar',
            'Khairpur',
            'Chishtian',
            'Charsadda',
            'Kandhkot',
            'Mianwali',
            'Tando Adam',
            'Dera Ismail Khan',
            'Kot Addu',
            'Nowshera',
            'Swabi',
            'Chakwal',
            'Tando Muhammad Khan',
            'Jaranwala',
            'Kandhkot',
            'Hasilpur',
            'Gojra',
            'Samundri',
            'Haveli Lakha',
            'Layyah',
            'Tank',
            'Chaman',
            'Bannu',
            'Haripur',
            'Attock',
            'Mansehra',
            'Lodhran',
            'Chakwal',
            'Chitral',
            'Kharan',
            'Kohlu',
            'Zhob',
            'Hub',
            'Gwadar',
            'Sibi',
        ] as $city)
                                                        <option value="{{ $city }}"
                                                            {{ old('taxes.0.tax_per_city') == $city ? 'selected' : '' }}>
                                                            {{ $city }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Local Tax <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="taxes[0][local_tax]"
                                                    value="{{ old('taxes.0.local_tax') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Append Button & Fields -->
                                    <div class="row col-md-12 mt-0 mb-2">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="addTaxBtn">
                                                Add Tax/City
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
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
        // Slug code
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".name").on("focusout", function(e) {
                    $(".slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
        // Image perview code
        function previewThumbnailImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result; // Update preview with the new image
            }
            if (event.target.files[0]) { // Check if a file was selected
                reader.readAsDataURL(event.target.files[0]);
            }
        }

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
            // Trigger change if any category is already selected
            if ($('#category').val().length > 0) {
                $('#category').trigger('change');
            }

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
                            populateSubCategoryDropdown(response);
                        },
                        error: function(xhr) {
                            console.error('Error fetching subcategories:', xhr);
                        }
                    });
                } else {
                    resetSubCategoryDropdown();
                }
            });

            // Callback function to populate subcategory dropdown
            function populateSubCategoryDropdown(response) {
                $('#sub_category').empty();

                // Store the old subcategory IDs for later use
                var oldSubCategories = @json(old('sub_category_id', []));

                // Create a set from the old subcategories for easier look-up
                var oldSubCategorySet = new Set(oldSubCategories);

                if (response.length > 0) {
                    response.forEach(function(subCategory) {
                        var isSelected = oldSubCategorySet.has(subCategory.id) ? 'selected' : '';
                        $('#sub_category').append('<option value="' +
                            subCategory.id + '" ' + isSelected + '>' +
                            subCategory.name + '</option>');
                    });

                    $('#sub_category').prop('disabled', false);
                } else {
                    $('#sub_category').append('<option value="">No Sub Category Available</option>');
                    $('#sub_category').prop('disabled', true);
                }
            }

            // Function to reset the subcategory dropdown
            function resetSubCategoryDropdown() {
                $('#sub_category').empty();
                $('#sub_category').append('<option value="">Select Sub Category</option>');
                $('#sub_category').prop('disabled', true);
            }
        });




        //#### Torster Message##########
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
        //################ Get Supplier Name ############
        // Fetch and populate supplier names
        let oldSupplierName = '{{ old('supplier_name_display') }}';
        let oldSupplierId = '{{ old('supplier_id_display') }}';

        $.ajax({
            url: '{{ route('getSuppliers') }}',
            type: 'GET',
            success: function(data) {
                let supplierNameDropdown = $('#supplier_name');
                supplierNameDropdown.empty().append(
                    '<option value="" disabled selected>Select Supplier Name</option>');

                data.forEach(function(supplier) {
                    supplierNameDropdown.append(
                        `<option value="${supplier.id}" ${oldSupplierName == supplier.id ? 'selected' : ''}>${supplier.name}</option>`
                    );
                });

                // If old value for supplier_name exists, manually trigger the change event to load supplier_id
                if (oldSupplierName) {
                    supplierNameDropdown.trigger('change');
                }
            },
            error: function(error) {
                console.log('Error fetching suppliers:', error);
            }
        });

        // Handle change event on Supplier Name dropdown
        $('#supplier_name').change(function() {
            let selectedSupplierId = $(this).val();
            let supplierIdDropdown = $('#supplier_id');

            if (selectedSupplierId) {
                // Find the selected supplier
                $.ajax({
                    url: '{{ route('getSuppliers') }}',
                    type: 'GET',
                    success: function(data) {
                        let selectedSupplier = data.find(supplier => supplier.id == selectedSupplierId);
                        if (selectedSupplier) {
                            supplierIdDropdown.empty().append(
                                `<option value="${selectedSupplier.supplier_id}" ${oldSupplierId == selectedSupplier.supplier_id ? 'selected' : ''}>${selectedSupplier.supplier_id}</option>`
                            );
                            supplierIdDropdown.prop('disabled', true);
                            // Store supplier name and ID in hidden fields
                            $('#supplier_name_hidden').val(selectedSupplier.name);
                            $('#supplier_id_hidden').val(selectedSupplier.supplier_id);
                        }
                    },
                    error: function(error) {
                        console.log('Error fetching suppliers:', error);
                    }
                });
            }
        });

        // ############# Add Taxes #############
        function addTax() {
            let taxCount = $('.tax-fields').length;
            let variantFieldHTML = `
    <div class="tax-fields border mt-1 col-md-12 mb-2">
        <div class="d-flex justify-content-end mt-1 mb-0">
            <i class="fa fa-trash btn btn-danger btn-sm removeVariantBtn"></i>
        </div>
        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label>Tax/City<span class="text-danger">*</span></label>
                <select name="taxes[${taxCount}][tax_per_city]" class="form-control select2">
                    <option value="" disabled selected>Select City</option>
                    <option value="Karachi">Karachi</option>
                    <option value="Lahore">Lahore</option>
                    <option value="Faisalabad">Faisalabad</option>
                    <option value="Rawalpindi">Rawalpindi</option>
                    <option value="Multan">Multan</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Gujranwala">Gujranwala</option>
                    <option value="Peshawar">Peshawar</option>
                    <option value="Quetta">Quetta</option>
                    <option value="Islamabad">Islamabad</option>
                    <option value="Sargodha">Sargodha</option>
                    <option value="Sialkot">Sialkot</option>
                    <option value="Bahawalpur">Bahawalpur</option>
                    <option value="Sukkur">Sukkur</option>
                    <option value="Larkana">Larkana</option>
                    <option value="Sheikhupura">Sheikhupura</option>
                    <option value="Mardan">Mardan</option>
                    <option value="Gujrat">Gujrat</option>
                    <option value="Rahim Yar Khan">Rahim Yar Khan</option>
                    <option value="Kasur">Kasur</option>
                    <option value="Okara">Okara</option>
                    <option value="Sahiwal">Sahiwal</option>
                    <option value="Wah Cantonment">Wah Cantonment</option>
                    <option value="Dera Ghazi Khan">Dera Ghazi Khan</option>
                    <option value="Mingora">Mingora</option>
                    <option value="Mirpur Khas">Mirpur Khas</option>
                    <option value="Chiniot">Chiniot</option>
                    <option value="Nawabshah">Nawabshah</option>
                    <option value="Kamoke">Kamoke</option>
                    <option value="Burewala">Burewala</option>
                    <option value="Jhelum">Jhelum</option>
                    <option value="Sadiqabad">Sadiqabad</option>
                    <option value="Khanewal">Khanewal</option>
                    <option value="Hafizabad">Hafizabad</option>
                    <option value="Kohat">Kohat</option>
                    <option value="Jacobabad">Jacobabad</option>
                    <option value="Shikarpur">Shikarpur</option>
                    <option value="Muzaffargarh">Muzaffargarh</option>
                    <option value="Abottabad">Abottabad</option>
                    <option value="Muridke">Muridke</option>
                    <option value="Jhang">Jhang</option>
                    <option value="Daska">Daska</option>
                    <option value="Mandi Bahauddin">Mandi Bahauddin</option>
                    <option value="Khuzdar">Khuzdar</option>
                    <option value="Pakpattan">Pakpattan</option>
                    <option value="Tando Allahyar">Tando Allahyar</option>
                    <option value="Vehari">Vehari</option>
                    <option value="Gojra">Gojra</option>
                    <option value="Mandi Bahauddin">Mandi Bahauddin</option>
                    <option value="Turbat">Turbat</option>
                    <option value="Dadu">Dadu</option>
                    <option value="Bahawalnagar">Bahawalnagar</option>
                    <option value="Khairpur">Khairpur</option>
                    <option value="Chishtian">Chishtian</option>
                    <option value="Charsadda">Charsadda</option>
                    <option value="Kandhkot">Kandhkot</option>
                    <option value="Mianwali">Mianwali</option>
                    <option value="Tando Adam">Tando Adam</option>
                    <option value="Dera Ismail Khan">Dera Ismail Khan</option>
                    <option value="Kot Addu">Kot Addu</option>
                    <option value="Nowshera">Nowshera</option>
                    <option value="Swabi">Swabi</option>
                    <option value="Chakwal">Chakwal</option>
                    <option value="Tando Muhammad Khan">Tando Muhammad Khan</option>
                    <option value="Jaranwala">Jaranwala</option>
                    <option value="Kandhkot">Kandhkot</option>
                    <option value="Hasilpur">Hasilpur</option>
                    <option value="Gojra">Gojra</option>
                    <option value="Samundri">Samundri</option>
                    <option value="Haveli Lakha">Haveli Lakha</option>
                    <option value="Layyah">Layyah</option>
                    <option value="Tank">Tank</option>
                    <option value="Chaman">Chaman</option>
                    <option value="Bannu">Bannu</option>
                    <option value="Haripur">Haripur</option>
                    <option value="Attock">Attock</option>
                    <option value="Mansehra">Mansehra</option>
                    <option value="Lodhran">Lodhran</option>
                    <option value="Chakwal">Chakwal</option>
                    <option value="Chitral">Chitral</option>
                    <option value="Kharan">Kharan</option>
                    <option value="Kohlu">Kohlu</option>
                    <option value="Zhob">Zhob</option>
                    <option value="Hub">Hub</option>
                    <option value="Gwadar">Gwadar</option>
                    <option value="Sibi">Sibi</option>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>Local Tax <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="taxes[${taxCount}][local_tax]"
                    value="">
            </div>
        </div>
    </div>
    `;
            $('#taxFields').append(variantFieldHTML);
            $('.select2').select2();
        }

        // Remove variant field
        $(document).on('click', '.removeVariantBtn', function() {

            $(this).closest('.tax-fields').remove();
        });
        // Event listener for Add Variant button
        $('#addTaxBtn').click(function() {
            addTax();
        });
    </script>
@endsection
