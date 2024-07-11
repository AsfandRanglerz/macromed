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
                                    <div class="form-group col-md-12">
                                        <label>Banner Image<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="banner_image">
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
                                            <select name="company" class="form-control" id="company">
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option {{ old('company') == $company->id ? 'selected' : '' }}
                                                        value="{{ $company->name }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Models <span class="text-danger">*</span></label>
                                            <select name="models" class="form-control" id="models">
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
                                                <div class="internet-error">No Internet Connection Found!</div>
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
                                    <div class="form-group col-md-12">
                                        <label>Short Description <span class="text-danger">*</span></label>
                                        <textarea name="short_description" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Long Description <span class="text-danger">*</span></label>
                                        <textarea name="long_description" cols="20" rows="50" class="long_description">{{ old('long_description') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
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
    </script>

@endsection
