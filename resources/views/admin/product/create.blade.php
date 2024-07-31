@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-md-12 col-md-12 col-lg-12">
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
                                                src="{{ asset('public/admin/assets/images/preview.png') }}"
                                                style="width: 15%" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Thumnail Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="thumbnail_image"
                                            onchange="previewThumnailImage(event)">
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
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Sub Category</label>
                                            <select name="sub_category_id[]" class="form-control select2" id="sub_category"
                                                multiple>
                                                <option value="">Select Sub Category</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Brand <span class="text-danger">*</span></label>
                                            <select name="brand_id[]" class="form-control select2" id="brand" multiple>
                                                @foreach ($brands as $brand)
                                                    <option {{ old('brand') == $brand->id ? 'selected' : '' }}
                                                        value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Certifications <span class="text-danger">*</span></label>
                                            <select name="certification_id[]" class="form-control select2"
                                                id="certification" multiple>
                                                @foreach ($certifications as $certification)
                                                    <option value="{{ $certification->id }}">{{ $certification->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Company</label>
                                            <select name="company" class="form-control select2" id="company">
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option {{ old('company') == $company->id ? 'selected' : '' }}
                                                        value="{{ $company->name }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Models <span class="text-danger">*</span></label>
                                            <select name="models" class="form-control select2" id="models">
                                                <option value="" disabled selected>Select Models</option>
                                                @foreach ($models as $model)
                                                    <option {{ old('models') == $model->id ? 'selected' : '' }}
                                                        value="{{ $model->name }}">{{ $model->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Country</label>
                                            <select name="country" class="form-control select2" id="country">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($countries == null)
                                                <div class="internet-error text-danger">No Internet Connection Found!</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Product Commission</label>
                                            <input type="text" class="form-control" name="product_commission"
                                                value="{{ old('product_commission') }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Video Link</label>
                                            <input type="text" class="form-control" name="video_link"
                                                value="{{ old('video_link') }}">
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-md-4">
                                            <label>Number Of Use<span class="text-danger">*</span></label>
                                            <select name="product_use_status" class="form-control select2">
                                                <option value="" disabled selected>Select Number Of Use</option>
                                                @foreach ($numberOfUses as $numberOfUse)
                                                    <option {{ old('numberOfUses') == $numberOfUse->id ? 'selected' : '' }}
                                                        value="{{ $numberOfUse->name }}">{{ $numberOfUse->name }}
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
                                                    <option
                                                        {{ old('sterilizations') == $sterilization->id ? 'selected' : '' }}
                                                        value="{{ $sterilization->name }}">{{ $sterilization->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Buyer Type <span class="text-danger">*</span></label>
                                            <select name="buyer_type" class="form-control select2">
                                                <option value="" disabled selected>Select Buyer Type</option>
                                                <option value="Option 1">Option 1</option>
                                                <option value="Option 2">Option 2</option>
                                                <option value="Option 3">Option 3</option>
                                                <option value="Option 4">Option 4</option>
                                                <option value="Option 5">Option 5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Product Class <span class="text-danger ">*</span></label>
                                            <select name="product_class" class="form-control select2">
                                                <option value="" disabled selected>Select Product Class</option>
                                                <option value="Class A">Class A</option>
                                                <option value="Clas B">Class B</option>
                                                <option value="Class C">Class C</option>
                                                <option value="Class D">Class D</option>
                                                <option value="Class E">Class E</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Main Material <span class="text-danger">*</span></label>
                                            <select name="material_id[]" class="form-control select2" multiple>
                                                @foreach ($mianMaterials as $mainMaterial)
                                                    <option
                                                        {{ old('mianMaterial') == $mainMaterial->id ? 'selected' : '' }}
                                                        value="{{ $mainMaterial->id }}">{{ $mainMaterial->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Suppliers Fields --}}
                                        <div class="form-group col-md-4">
                                            <label>Supplier Name <span class="text-danger">*</span></label>
                                            <select id="supplier_name" name="supplier_name_display"
                                                class="form-control select2">
                                                <!-- Options will be populated by AJAX -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-4">
                                            <label>Supplier Id <span class="text-danger">*</span></label>
                                            <select id="supplier_id" name="supplier_id_display"
                                                class="form-control select2" disabled>
                                                <!-- Options will be populated by AJAX -->
                                            </select>
                                        </div>
                                        <!-- Hidden fields to store actual values -->
                                        <input type="hidden" id="supplier_name_hidden" name="supplier_name">
                                        <input type="hidden" id="supplier_id_hidden" name="supplier_id">
                                        {{-- Delivery Fields --}}
                                        <div class="form-group col-md-4">
                                            <label>Supplier Delivery Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="supplier_delivery_time"
                                                value="{{ old('supplier_delivery_time') }}">
                                        </div>
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
                                            <label>Self Life<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="self_life"
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
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-6">
                                            <label>Minimum Price Range<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="min_price_range"
                                                value="{{ old('min_price_range') }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Maximum Price Range <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="max_price_range"
                                                value="{{ old('max_price_range') }}">
                                        </div>
                                    </div>
                                    <hr>
                                    {{-- Tabs  --}}
                                    <div class="row col-md-12">
                                        <h4 class="col-md-12">Tabs Content:</h4>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 1 Heading<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tab_1_heading"
                                                value="{{ old('tab_1_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 1 Content<span class="text-danger">*</span></label>
                                            <textarea name="tab_1_text" cols="20" rows="50" class="long_description">{{ old('tab_1_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 2 Heading<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tab_2_heading"
                                                value="{{ old('tab_2_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 2 Content<span class="text-danger">*</span></label>
                                            <textarea name="tab_2_text" cols="20" rows="50" class="long_description">{{ old('tab_2_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 3 Heading<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tab_3_heading"
                                                value="{{ old('tab_3_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 3 Content<span class="text-danger">*</span></label>
                                            <textarea name="tab_3_text" cols="20" rows="50" class="long_description">{{ old('tab_3_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-12">
                                            <label>Tab 4 Heading<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tab_4_heading"
                                                value="{{ old('tab_4_heading') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Tab 4 Content<span class="text-danger">*</span></label>
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

        //#### Torster Message##########
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
        //################ Get Supplier Name ############
        // Fetch and populate supplier names
        $.ajax({
            url: '{{ route('getSuppliers') }}',
            type: 'GET',
            success: function(data) {
                let supplierNameDropdown = $('#supplier_name');
                supplierNameDropdown.append('<option value="" disabled selected>Select Supplier Name</option>');
                data.forEach(function(supplier) {
                    supplierNameDropdown.append(
                        `<option value="${supplier.id}">${supplier.name}</option>`
                    );
                });
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
                                `<option value="${selectedSupplier.supplier_id}">${selectedSupplier.supplier_id}</option>`
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
