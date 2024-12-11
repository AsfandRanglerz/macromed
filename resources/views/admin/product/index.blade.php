@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    <style>
        .modal-fullscreen {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            top: 0;
            left: 0;
        }

        #modal-dialog {
            width: 100%;
            height: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
        }

        .modal-content {
            height: 100%;
            border: none;
            border-radius: 0;
        }

        .modal-body {
            overflow-y: auto;
        }
    </style>
    <!-- Delete Subadmin Modal -->
    <div class="modal fade" id="deleteSubadminModal" tabindex="-1" role="dialog" aria-labelledby="deleteSubadminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this product?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteSubadmin">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit product --}}
    <div class="modal fade" id="editModelsModal" tabindex="-1" role="dialog" aria-labelledby="editModelsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-xl" role="document" id="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModelsModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editModels" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-md-12">
                            <label>Thumbnail Image Preview</label>
                            <div>
                                <img id="preview-img" class="admin-img"
                                    src="{{ asset('public/admin/assets/images/preview.png') }}" style="width: 15%"
                                    alt="">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Thumnail Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file thumbnail_image" name="thumbnail_image"
                                onchange="previewThumnailImage(event)">
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-4">
                                <label>Product Short Name<span class="text-danger">*</span></label>
                                <input type="text" id="short_name" class="form-control short_name" name="short_name"
                                    value="{{ old('short_name') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Product Name<span class="text-danger">*</span></label>
                                <input type="text" id="product_name" class="form-control name product_name"
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
                                <select name="category_id[]" class="form-control select2 category" id="category" multiple
                                    style="width: 100%">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sub Category</label>
                                <select name="sub_category_id[]" class="form-control select2 sub_category" id="sub_category"
                                    multiple style="width: 100%">
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Brand <span class="text-danger">*</span></label>
                                <select name="brand_id[]" class="form-control select2 brand" id="brand" multiple
                                    style="width: 100%">
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
                                <select name="certification_id[]" class="form-control select2 certification"
                                    id="certification" multiple style="width: 100%">
                                    @foreach ($certifications as $certification)
                                        <option value="{{ $certification->id }}">{{ $certification->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Company</label>
                                <select name="company" class="form-control select2 company" id="company"
                                    style="width: 100%">
                                    <option value="" disabled selected>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option {{ old('company') == $company->id ? 'selected' : '' }}
                                            value="{{ $company->name }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group col-md-4">
                                <label>Models <span class="text-danger">*</span></label>
                                <select name="models" class="form-control select2 models" id="models"
                                    style="width: 100%">
                                    <option value="" disabled selected>Select Models</option>
                                    @foreach ($models as $model)
                                        <option {{ old('models') == $model->id ? 'selected' : '' }}
                                            value="{{ $model->name }}">{{ $model->name }}</option>
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
                                <select name="country" class="form-control select2 country" id="country"
                                    style="width: 100%">
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
                                <label>Product Commission <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control product_commission"
                                        name="product_commission" value="{{ old('product_commission') }}">
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
                                <input type="text" class="form-control video_link" name="video_link"
                                    value="{{ old('video_link') }}">
                            </div>
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-md-4">
                                <label>Number Of Use<span class="text-danger">*</span></label>
                                <select name="product_use_status" class="form-control select2 product_use_status"
                                    style="width: 100%">
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
                                <select name="sterilizations" class="form-control select2 sterilizations"
                                    id="sterilizations" style="width: 100%">
                                    <option value="" disabled selected>Select Sterilizations</option>
                                    @foreach ($sterilizations as $sterilization)
                                        <option {{ old('sterilizations') == $sterilization->id ? 'selected' : '' }}
                                            value="{{ $sterilization->name }}">{{ $sterilization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Buyer Type <span class="text-danger">*</span></label>
                                <select name="buyer_type" class="form-control select2 buyer_type" style="width: 100%">
                                    <option value="" disabled selected>Select Buyer Type</option>
                                    <option value="Individual Customer">Individual Customer</option>
                                    <option value="Clinic">Clinic</option>
                                    <option value="Private Hospital">Private Hospital</option>
                                    <option value="Govt. Hospital">Govt. Hospital</option>
                                    <option value="Reseller">Reseller</option>
                                    <option value="Reseller">Reseller</option>

                                </select>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-4">
                                <label>Product Class <span class="text-danger ">*</span></label>
                                <select name="product_class" class="form-control select2 product_class"
                                    style="width: 100%">
                                    <option value="" disabled selected>Select Product Class</option>
                                    <option value="Class A-1">Class A-1</option>
                                    <option value="Class B-2">Class B-2</option>
                                    <option value="Class C-3">Class C-3</option>
                                    <option value="Class D-4">Class D-4</option>
                                    <option value="Class E-5">Class E-5</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Main Material <span class="text-danger">*</span></label>
                                <select name="material_id[]" class="form-control select2 material_id" multiple
                                    style="width: 100%">
                                    @foreach ($mianMaterials as $mainMaterial)
                                        <option {{ old('mianMaterial') == $mainMaterial->id ? 'selected' : '' }}
                                            value="{{ $mainMaterial->id }}">{{ $mainMaterial->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Suppliers Fields --}}
                            <div class="form-group col-md-4">
                                <label>Supplier Name <span class="text-danger">*</span></label>
                                <select id="supplier_name" name="supplier_name"
                                    class="form-control select2 supplier_name" style="width: 100%">
                                    @foreach ($suppliers as $supplier)
                                        <option {{ old('supplier') == $supplier->id ? 'selected' : '' }}
                                            value="{{ $supplier->name }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-4">
                                <label>Supplier Id <span class="text-danger">*</span></label>
                                <select id="supplier_id" name="supplier_id" class="form-control select2 supplier_id"
                                    disabled style="width: 100%">
                                    @foreach ($suppliers as $supplier)
                                        <option {{ old('supplier') == $supplier->id ? 'selected' : '' }}
                                            value="{{ $supplier->supplier_id }}">
                                            {{ $supplier->supplier_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Delivery Fields --}}
                            <div class="form-group col-md-4">
                                <label>Supplier Delivery Period <span class="text-danger">*</span></label>
                                <input type="text" class="form-control supplier_delivery_time"
                                    name="supplier_delivery_time" value="{{ old('supplier_delivery_time') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Delivery Period <span class="text-danger">*</span></label>
                                <input type="text" class="form-control delivery_period" name="delivery_period"
                                    value="{{ old('delivery_period') }}">
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-12">
                                <label>Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" cols="30" rows="10" class="form-control text-area-5 short_description">{{ old('short_description') }}</textarea>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="form-group col-md-12">
                                <label>Long Description <span class="text-danger">*</span></label>
                                <textarea name="long_description" cols="20" rows="50" class="long_description text-area-5">{{ old('long_description') }}</textarea>
                            </div>
                        </div>

                        <div class="row col-md-12">
                            <div class="form-group col-md-4">
                                <label>Shelf Life / Expiry Period<span class="text-danger">*</span></label>
                                <input type="text" class="form-control self_life" name="self_life"
                                    value="{{ old('self_life') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Condition <span class="text-danger">*</span></label>
                                <select name="product_condition" class="form-control select2 product_condition"
                                    style="width: 100%">
                                    <option value="" disabled selected>Select Conditions</option>
                                    @foreach ($conditions as $condition)
                                        <option value="{{ $condition->name }}"
                                            {{ old('product_condition') == $condition->name ? 'selected' : '' }}>
                                            {{ $condition->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
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
                                <label>Tab 1 Heading<span class="text-danger">*</span></label>
                                <input type="text" class="form-control tab_1_heading" name="tab_1_heading"
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
                                <input type="text" class="form-control tab_2_heading" name="tab_2_heading"
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
                                <input type="text" class="form-control tab_3_heading" name="tab_3_heading"
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
                                <input type="text" class="form-control tab_4_heading" name="tab_4_heading"
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
                                <input type="text" class="form-control federal_tax" name="federal_tax"
                                    value="{{ old('federal_tax') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Provincial Tax <span class="text-danger">*</span></label>
                                <input type="text" class="form-control provincial_tax" name="provincial_tax"
                                    value="{{ old('provincial_tax') }}">
                            </div>
                        </div>
                        <div class="tax-fields" id="taxFields">

                        </div>
                        <!-- Append Button & Fields -->
                        <div class="row col-md-12 mt-0 mb-2">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" id="addTaxBtn">
                                    Add Tax/City
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateModels()">Update</button>
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
                                    <h4>Products</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3 text-white" href="{{ route('product.create') }}">
                                    Create Product
                                </a>

                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Brands</th>
                                            <th>Certifications</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Company</th>
                                            <th>Product HTS Code</th>
                                            <th>Country</th>
                                            <th>Supplier Name</th>
                                            <th>Number Of Use</th>
                                            <th>Product Status</th>
                                            <th>Status</th>
                                            <th>Variants</th>
                                            <th>Discounts</th>
                                            <th>Uploads Images</th>
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
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".product_name").on("focusout", function(e) {
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

        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('product.get') }}",
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
                        "data": "product_code",
                        "render": function(data, type, row) {
                            return '#' + data;
                        }
                    },
                    {
                        "data": "product_name",
                        "render": function(data, type, row) {
                            function truncateText(text, wordLimit) {
                                var words = text.split(' ');
                                if (words.length > wordLimit) {
                                    return words.slice(0, wordLimit).join(' ') + '...';
                                }
                                return text;
                            }
                            return truncateText(data, 4);
                        }
                    },
                    {
                        "data": "product_brands",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return '<span class="badge bg-danger text-white">No data found!</span>';
                            }
                            return data.map(productBrand => productBrand.brands.name).join(', ');
                        }
                    },
                    {
                        "data": "product_certifications",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return '<span class="badge bg-danger text-white">No data found!</span>';
                            }
                            return data.map(certification => certification.certification.name).join(
                                ', ');
                        }
                    },
                    {
                        "data": "product_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return '<span class="badge bg-danger text-white">No data found!</span>';
                            }
                            return data.map(productCategory => productCategory.categories.name)
                                .join(', ');
                        }
                    },
                    {
                        "data": "product_sub_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return '<span class="badge bg-danger text-white">No data found!</span>';
                            }
                            return data.map(productSubCategory => productSubCategory.sub_categories
                                    .name)
                                .join(', ');
                        }
                    },
                    {
                        "data": "company"
                    },
                    {
                        "data": "product_hts"
                    },
                    {
                        "data": "country"
                    },
                    {
                        "data": "supplier_name"
                    },
                    {
                        "data": "product_use_status"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.product_status == 'Featured Product' ?
                                'btn-success' :
                                'btn-danger';
                            var buttonText = row.product_status == 'Featured Product' ?
                                'Featured Product' :
                                'New Product';
                            return '<button id="update-productfeature-status" class="btn ' +
                                buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

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
                        "render": function(data, type, row) {
                            return `
                    <div class="dropdown d-inline">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton${row.id}">
                            <a class="dropdown-item has-icon" href="{{ route('product_variant_index.index', ':id') }}"><i class="fas fa-eye"></i>View</a>
                            <a class="dropdown-item has-icon" href="{{ route('product_variant.index', ':id') }}"><i class="fas fa-plus"></i>Add</a>
                        </div>
                    </div>
                `.replace(/:id/g, row.id);
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('productDiscounts.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 text-white"><i class="fas fa-tag"></i></a>';
                        },
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('product.image', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-0 text-white"><i class="fas fa-image"></i></a>';
                        },
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-1 mr-1 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-1 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';

                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editModelsModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });
        });

        // ######Get & Update Models#########
        // ############# Add Taxes #############
        const cities = ["Karachi", "Lahore", "Faisalabad", "Rawalpindi", "Multan", "Hyderabad", "Gujranwala",
            "Peshawar", "Quetta", "Islamabad", "Sargodha", "Sialkot", "Bahawalpur", "Sukkur", "Larkana",
            "Sheikhupura", "Mardan", "Gujrat", "Rahim Yar Khan", "Kasur", "Okara", "Sahiwal", "Wah Cantonment",
            "Dera Ghazi Khan", "Mingora", "Mirpur Khas", "Chiniot", "Nawabshah", "Kamoke", "Burewala", "Jhelum",
            "Sadiqabad", "Khanewal", "Hafizabad", "Kohat", "Jacobabad", "Shikarpur", "Muzaffargarh", "Abottabad",
            "Muridke", "Jhang", "Daska", "Mandi Bahauddin", "Khuzdar", "Pakpattan", "Tando Allahyar", "Vehari",
            "Gojra", "Mandi Bahauddin", "Turbat", "Dadu", "Bahawalnagar", "Khairpur", "Chishtian", "Charsadda",
            "Kandhkot", "Mianwali", "Tando Adam", "Dera Ismail Khan", "Kot Addu", "Nowshera", "Swabi", "Chakwal",
            "Tando Muhammad Khan", "Jaranwala", "Kandhkot", "Hasilpur", "Gojra", "Samundri", "Haveli Lakha",
            "Layyah", "Tank", "Chaman", "Bannu", "Haripur", "Attock", "Mansehra", "Lodhran", "Chakwal", "Chitral",
            "Kharan", "Kohlu", "Zhob", "Hub", "Gwadar", "Sibi"
        ];

        function getCityOptions(selectedCity = '') {
            return cities.map(city => `<option value="${city}" ${city === selectedCity ? 'selected' : ''}>${city}</option>`)
                .join('');
        }

        function updateTaxFields() {
            $('#taxFields .tax-fields').each(function(index, element) {
                $(element).find('select').attr('name', `taxes[${index}][tax_per_city]`);
                $(element).find('input').attr('name', `taxes[${index}][local_tax]`);
            });
        }

        function addTaxField(tax = {}) {
            let taxFieldHTML = `
        <div class="tax-fields border mt-1 col-md-12 mb-2">
            <div class="d-flex justify-content-end mt-1 mb-0">
                <i class="fa fa-trash btn btn-danger btn-sm removeVariantBtn"></i>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label>Tax/City<span class="text-danger">*</span></label>
                    <select class="form-control select2" name="taxes[0][tax_per_city]" style="width: 100%">
                        <option value="" disabled ${!tax.tax_per_city ? 'selected' : ''}>Select City</option>
                        ${getCityOptions(tax.tax_per_city || '')}
                    </select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>Local Tax <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="taxes[0][local_tax]" value="${tax.local_tax || ''}">
                </div>
            </div>
        </div>`;
            $('#taxFields').append(taxFieldHTML);
            $('.select2').select2();
            updateTaxFields();
        }

        $(document).on('click', '.removeVariantBtn', function() {
            $(this).closest('.tax-fields').remove();
            updateTaxFields();
        });

        $('#addTaxBtn').click(function() {
            addTaxField();
        });

        function editModelsModal(id) {
            var showModels = '{{ route('product.show', ':id') }}';
            $.ajax({
                url: showModels.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    if (response.status != "1") {
                        toastr.error('This product is inactive. You cannot edit the product.');
                        return;
                    }
                    var imageUrl = response.thumbnail_image;
                    var baseUrl = 'https://macromed.com.pk/admin/';
                    var responseImage = baseUrl + response.thumbnail_image;
                    if (imageUrl) {
                        $('.admin-img').attr('src', responseImage).show();
                    } else {
                        $('.admin-img').hide();
                    }
                    $('#editModels .short_name').val(response.short_name);
                    $('#editModels .banner_image').val(response.banner_image);
                    $('#editModels .product_name').val(response.product_name);
                    $('#editModels .slug').val(response.slug);
                    $('#editModels .company').val(response.company).trigger('change');
                    $('#editModels .country').val(response.country).trigger('change');
                    $('#editModels .models').val(response.models).trigger('change');
                    $('#editModels .video_link').val(response.video_link);
                    $('#editModels .product_commission').val(response.product_commission);
                    $('#editModels .status').val(response.status);
                    $('#editModels .short_description').val(response.short_description);
                    $('#editModels .product_hts').val(response.product_hts);
                    $('#editModels .sterilizations').val(response.sterilizations).trigger('change');
                    $('#editModels .product_use_status').val(response.product_use_status).trigger('change');
                    $('#editModels .buyer_type').val(response.buyer_type).trigger('change');
                    $('#editModels .product_class').val(response.product_class).trigger('change');
                    $('#editModels .supplier_name').val(response.supplier_name).trigger('change');
                    $('#editModels .supplier_id').val(response.supplier_id).trigger('change');
                    $('#editModels .supplier_delivery_time').val(response.supplier_delivery_time);
                    $('#editModels .delivery_period').val(response.delivery_period);
                    $('#editModels .self_life').val(response.self_life);
                    $('#editModels .federal_tax').val(response.federal_tax);
                    $('#editModels .provincial_tax').val(response.provincial_tax);
                    $('#editModels .supplier_name').val(response.supplier_name).trigger('change');
                    $('#editModels .tab_1_heading').val(response.tab_1_heading);
                    $('#editModels .tab_1_text').val(response.tab_1_text);
                    $('#editModels .tab_2_heading').val(response.tab_2_heading);
                    $('#editModels .tab_2_text').val(response.tab_2_text);
                    $('#editModels .tab_3_heading').val(response.tab_3_heading);
                    $('#editModels .tab_3_text').val(response.tab_3_text);
                    $('#editModels .tab_4_heading').val(response.tab_4_heading);
                    $('#editModels .tab_4_text').val(response.tab_4_text);
                    $('#editModels .product_condition').val(response.product_condition).trigger('change');
                    if (response.long_description !== null) {
                        editors[0].setData(response.long_description);
                    } else {
                        editors[0].setData('');
                    }

                    if (response.tab_1_text !== null) {
                        editors[1].setData(response.tab_1_text);
                    } else {
                        editors[1].setData('');
                    }

                    if (response.tab_2_text !== null) {
                        editors[2].setData(response.tab_2_text);
                    } else {
                        editors[2].setData('');
                    }

                    if (response.tab_3_text !== null) {
                        editors[3].setData(response.tab_3_text);
                    } else {
                        editors[3].setData('');
                    }

                    if (response.tab_4_text !== null) {
                        editors[4].setData(response.tab_4_text);
                    } else {
                        editors[4].setData('');
                    }
                    // Category
                    let categoryIds = [];
                    if (response.product_category) {
                        categoryIds = response.product_category.map(category => category.categories.id);
                    }

                    $('#editModels .category').val(categoryIds).trigger('change');
                    // Sub Category
                    let subCategoryId = [];
                    if (response.product_sub_category && response.product_sub_category.length > 0) {
                        subCategoryId = response.product_sub_category.map(subCategory => subCategory
                            .sub_categories.id);
                        fetchSubcategoriesByCategory(categoryIds, function() {
                            $('#sub_category').val(subCategoryId).trigger('change');
                        });
                    } else {
                        fetchSubcategoriesByCategory(categoryIds);
                    }
                    // Brand
                    let brands = [];
                    if (response.product_brands) {
                        brands = response.product_brands.map(brands => brands.brands.id);
                    }
                    $('#editModels .brand').val(brands).trigger('change');
                    // Certification
                    let certification = [];
                    if (response.product_certifications) {
                        certification = response.product_certifications.map(certification => certification
                            .certification.id);
                    }
                    $('#editModels .certification').val(certification).trigger('change');
                    // Material
                    let mainMaterialId = [];
                    if (response.product_material) {
                        mainMaterialId = response.product_material.map(mainMaterial => mainMaterial
                            .main_material.id);
                    }
                    $('#editModels .material_id').val(mainMaterialId).trigger('change');

                    // Product Tax
                    if (response.product_tax && response.product_tax.length > 0) {
                        $('#taxFields').empty();
                        response.product_tax.forEach(function(tax) {
                            addTaxField(tax);
                        });
                    } else {
                        addTaxField();
                    }

                    $('.select2').select2();
                    $('#editModelsModal').modal('show');
                    $('#editModelsModal').data('id', id);
                }
            });
        }

        // #############Update subAdmin#############
        function updateModels() {
            var updateModels = '{{ route('product.update', ':id') }}';
            var id = $('#editModelsModal').data('id');
            var form = document.getElementById("editModels");
            var formData = new FormData(form);
            formData.append('long_description', editors[0] ? editors[0].getData() : '');
            formData.append('tab_1_text', editors[1] ? editors[1].getData() : '');
            formData.append('tab_2_text', editors[2] ? editors[2].getData() : '');
            formData.append('tab_3_text', editors[3] ? editors[3].getData() : '');
            formData.append('tab_4_text', editors[4] ? editors[4].getData() : '');

            var category_id = [];
            $('select[name="category_id[]"] option:selected').each(function(index, element) {
                category_id.push($(element).val());
            });
            formData.append('category_id', JSON.stringify(category_id));

            var sub_category_id = [];
            $('select[name="sub_category_id[]"] option:selected').each(function(index, element) {
                sub_category_id.push($(element).val());
            });
            formData.append('sub_category_id', JSON.stringify(sub_category_id));

            var brand_id = [];
            $('select[name="brand_id[]"] option:selected').each(function(index, element) {
                brand_id.push($(element).val());
            });
            formData.append('brand_id', JSON.stringify(brand_id));

            var certification_id = [];
            $('select[name="certification_id[]"] option:selected').each(function(index, element) {
                certification_id.push($(element).val());
            });
            formData.append('certification_id', JSON.stringify(certification_id));

            var material_id = [];
            $('select[name="material_id[]"] option:selected').each(function(index, element) {
                material_id.push($(element).val());
            });
            formData.append('material_id', JSON.stringify(material_id));

            $('.tax-fields').each(function(index, element) {
                let taxPerCity = $(element).find('select[name^="taxes["]').val();
                let localTax = $(element).find('input[name^="taxes["]').val();
                if (taxPerCity && localTax) {
                    formData.append(`taxes[${index}][tax_per_city]`, taxPerCity);
                    formData.append(`taxes[${index}][local_tax]`, localTax);
                }
            });

            $.ajax({
                url: updateModels.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Product Updated Successfully!');
                    $('#editModelsModal').modal('hide');
                    reloadDataTable();
                    $('#editModelsModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        console.log(xhr);

                        toastr.error('An error occurred while updating the product.');
                    }
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
                url: '{{ route('productsBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
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
        // ################ Product Status ############

        $('#example').on('click', '#update-productfeature-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentProductStatus = button.text().trim().toLowerCase();
            var newProductStatus = currentProductStatus === 'Active' ? 'Featured Product' : 'New Product';
            button.prop('disabled', true);

            $.ajax({
                url: '{{ route('productsFeature.update', ['id' => ':userId']) }}'.replace(':userId',
                    userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_status: newProductStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var productButtonText = newProductStatus === 'Featured Product' ?
                        'Featured Product' :
                        'New Product';
                    var productButtonClass = newProductStatus === 'Featured Product' ? 'btn-success' :
                        'btn-danger';
                    button.text(productButtonText).removeClass('btn-success btn-danger').addClass(
                        productButtonClass);
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
        // ############# Delete Subadmin Data###########
        function deleteSubadminModal(subadminId) {
            $('#confirmDeleteSubadmin').data('subadmin-id', subadminId);
            $('#deleteSubadminModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var subadminId = $(this).data('subadmin-id');
                deleteSubadmin(subadminId);
            });
        });

        function deleteSubadmin(subadminId) {
            $.ajax({
                url: "{{ route('product.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                    subadminId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Product Deleted Successfully!');
                    $('#deleteSubadminModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
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

        function previewThumnailImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };

        function fetchSubcategoriesByCategory(selectedCategories, callback = null) {
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
                                $('#sub_category').append('<option value="' + subCategory.id + '">' +
                                    subCategory.name + '</option>');
                            });
                            $('#sub_category').prop('disabled', false);
                        } else {
                            $('#sub_category').append('<option value="">No Sub Category Available</option>');
                            $('#sub_category').prop('disabled', true);
                        }
                        if (callback) callback();
                    }
                });
            } else {
                $('#sub_category').empty();
                $('#sub_category').append('<option value="">Select Sub Category</option>');
                $('#sub_category').prop('disabled', true);
                if (callback) callback();
            }
        }

        $('#category').change(function() {
            var selectedCategories = $(this).val();
            fetchSubcategoriesByCategory(selectedCategories);
        });

        //################ Get Supplier Name ############
        $('#supplier_name').change(function() {
            var selectedName = $(this).val();
            $.ajax({
                url: '{{ route('getSuppliers') }}',
                method: 'GET',
                success: function(response) {
                    var suppliers = response;
                    var supplierIdDropdown = $('#supplier_id');
                    supplierIdDropdown.empty(); // Clear existing options

                    $.each(suppliers, function(index, supplier) {
                        if (supplier.name == selectedName) {
                            supplierIdDropdown.append(new Option(supplier.supplier_id, supplier
                                .supplier_id));
                        }
                    });

                    supplierIdDropdown.prop('disabled', true); // Enable the dropdown
                }
            });
        });
    </script>

@endsection
