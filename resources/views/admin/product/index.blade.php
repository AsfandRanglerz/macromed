@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModelsModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="editModels" enctype="multipart/form-data">
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
                                <select name="category_id[]" class="form-control select2 category" id="category"
                                    style="width: 100%" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sub Category</label>
                                <select name="sub_category_id[]" class="form-control select2 sub_category" id="sub_category"
                                    style="width: 100%" multiple>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Brand <span class="text-danger">*</span></label>
                                <select name="brand_id[]" class="form-control select2 brand" id="brand"
                                    style="width: 100%" multiple>
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
                                    style="width: 100%" id="certification" multiple>
                                    @foreach ($certifications as $certification)
                                        <option value="{{ $certification->id }}">{{ $certification->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Company</label>
                                <select name="company" class="form-control company" style="width: 100%" id="company">
                                    <option value="" disabled selected>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option {{ old('company') == $company->id ? 'selected' : '' }}
                                            value="{{ $company->name }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Models <span class="text-danger">*</span></label>
                                <select name="models" class="form-control models" style="width: 100%" id="models">
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
                                <select name="country" class="form-control select2 country" style="width: 100%"
                                    id="country">
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
                                <input type="text" class="form-control product_commission" name="product_commission"
                                    value="{{ old('product_commission') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Video Link</label>
                                <input type="text" class="form-control video_link" name="video_link"
                                    value="{{ old('video_link') }}">
                            </div>
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-md-4">
                                <label>Product Status <span class="text-danger">*</span></label>
                                <select name="product_use_status" class="form-control product_use_status">
                                    <option value="" disabled selected>Select Product Status</option>
                                    <option value="1">Disposable</option>
                                    <option value="0">Multiple Use</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sterilizations <span class="text-danger">*</span></label>
                                <select name="sterilizations" class="form-control sterilizations" id="sterilizations">
                                    <option value="" disabled selected>Select Sterilizations</option>
                                    @foreach ($sterilizations as $sterilization)
                                        <option {{ old('sterilizations') == $sterilization->id ? 'selected' : '' }}
                                            value="{{ $sterilization->name }}">{{ $sterilization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Short Description <span class="text-danger">*</span></label>
                            <textarea name="short_description" cols="30" rows="10" class="form-control text-area-5 short_description">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Long Description <span class="text-danger">*</span></label>
                            <textarea name="long_description" cols="20" rows="50" class="long_description text-area-5">{{ old('long_description') }}</textarea>
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
                                            <th>Product Name</th>
                                            <th>Brands</th>
                                            <th>Certifications</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Company</th>
                                            <th>Models</th>
                                            <th>Status</th>
                                            <th>Variants</th>
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
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('products.get') }}",
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
                                return 'No data found!';
                            }
                            return data.map(productBrand => productBrand.brands.name).join(', ');
                        }
                    },
                    {
                        "data": "product_certifications",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return 'No data found!';
                            }
                            return data.map(certification => certification.certification.name).join(
                                ', ');
                        }
                    },
                    {
                        "data": "product_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return 'No data found!';
                            }
                            return data.map(productCategory => productCategory.categories.name)
                                .join(', ');
                        }
                    },
                    {
                        "data": "product_sub_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return 'No data found!';
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
                        "data": "models"
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

        function editModelsModal(id) {
            var showModels = '{{ route('product.show', ':id') }}';
            $.ajax({
                url: showModels.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editModels .short_name').val(response.short_name);
                    $('#editModels .thumbnail_image').val(response.thumbnail_image);
                    $('#editModels .banner_image').val(response.banner_image);
                    $('#editModels .product_name').val(response.product_name);
                    $('#editModels .slug').val(response.slug);
                    $('#editModels .company').val(response.company);
                    $('#editModels .country').val(response.country);
                    $('#editModels .models').val(response.models);
                    $('#editModels .video_link').val(response.video_link);
                    $('#editModels .product_commission').val(response.product_commission);
                    $('#editModels .status').val(response.status);
                    $('#editModels .short_description').val(response.short_description);
                    $('#editModels .sterilizations').val(response.sterilizations);
                    $('#editModels .product_use_status').val(response.product_use_status);
                    if (response.long_description !== null) {
                        geteditor.setData(response.long_description);
                    } else {
                        geteditor.setData(''); // Or set it to an empty string
                    }
                    let categoryIds = [];
                    let brands = [];
                    let certification = [];
                    let subCategoryId = [];

                    if (response.product_category) {
                        categoryIds = response.product_category.map(category => category.categories.id);
                    }
                    $('#editModels .category').val(categoryIds).trigger('change');

                    if (response.product_sub_category) {
                        subCategoryId = response.product_sub_category.map(subCategory => subCategory
                            .sub_categories.id);
                    }
                    $('#editModels .sub_category').val(subCategoryId).trigger('change');

                    if (response.product_brands) {
                        brands = response.product_brands.map(brands => brands.brands.id);
                    }
                    $('#editModels .brand').val(brands).trigger('change');

                    if (response.product_certifications) {
                        certification = response.product_certifications.map(certification => certification
                            .certification.id);
                    }
                    $('#editModels .certification').val(certification).trigger('change');
                    $('#editModelsModal').modal('show');
                    $('#editModelsModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        function updateModels() {
            var updateModels = '{{ route('product.update', ':id') }}';
            var id = $('#editModelsModal').data('id');
            var form = document.getElementById("editModels");
            var short_name = form["short_name"].value;
            var product_name = form["product_name"].value;
            var slug = form["slug"].value;
            var company = form["company"].value;
            var models = form["models"].value;
            var country = form["country"].value;
            var video_link = form["video_link"].value;
            var product_commission = form["product_commission"].value; // Fixed typo here
            var short_description = form["short_description"].value;
            var status = form["status"].value;
            var product_use_status = form["product_use_status"].value;
            var sterilizations = form["sterilizations"].value;
            var long_description = geteditor.getData();

            var category_id = [];
            $('select[name="category_id[]"] option:selected').each(function(index, element) {
                category_id.push($(element).val());
            });

            var sub_category_id = [];
            $('select[name="sub_category_id[]"] option:selected').each(function(index, element) {
                sub_category_id.push($(element).val());
            });

            var brand_id = [];
            $('select[name="brand_id[]"] option:selected').each(function(index, element) {
                brand_id.push($(element).val());
            });

            var certification_id = [];
            $('select[name="certification_id[]"] option:selected').each(function(index, element) {
                certification_id.push($(element).val());
            });

            // Form Data
            var formData = new FormData();
            formData.append('short_name', short_name);
            formData.append('product_name', product_name);
            formData.append('slug', slug);
            formData.append('company', company);
            formData.append('models', models);
            formData.append('video_link', video_link);
            formData.append('product_commission', product_commission);
            formData.append('short_description', short_description);
            formData.append('status', status);
            formData.append('country', country);
            formData.append('product_use_status', product_use_status);
            formData.append('sterilizations', sterilizations);
            formData.append('long_description', long_description);
            formData.append('category_id', JSON.stringify(category_id));
            formData.append('sub_category_id', JSON.stringify(sub_category_id));
            formData.append('brand_id', JSON.stringify(brand_id));
            formData.append('certification_id', JSON.stringify(certification_id));
            // console.log('data',JSON.stringify(Object.fromEntries(formData)));
            // return ;
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
                        toastr.error(error);
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

        let geteditor;
        ClassicEditor
            .create(document.querySelector('.long_description'))
            .then(newGetEditor => {
                geteditor = newGetEditor;
            })
            .catch(error => {
                console.error(error);
            });

        function previewThumnailImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };

        // Show sub Categories against Category
        // $(document).ready(function() {
        //     $('#category').change(function() {
        //         var selectedCategories = $(this).val();
        //         if (selectedCategories.length > 0) {
        //             $.ajax({
        //                 url: '{{ route('category.subCategories') }}',
        //                 type: 'GET',
        //                 data: {
        //                     category_ids: selectedCategories
        //                 },
        //                 success: function(response) {
        //                     $('#sub_category').empty();
        //                     if (response.length > 0) {
        //                         response.forEach(function(subCategory) {
        //                             $('#sub_category').append('<option value="' +
        //                                 subCategory.id + '">' + subCategory.name +
        //                                 '</option>');
        //                         });
        //                         $('#sub_category').prop('disabled', false);
        //                     } else {
        //                         $('#sub_category').append(
        //                             '<option value="">No Sub Category Available</option>');
        //                         $('#sub_category').prop('disabled', true);
        //                     }
        //                 }
        //             });
        //         } else {
        //             $('#sub_category').empty();
        //             $('#sub_category').append('<option value="">Select Sub Category</option>');
        //             $('#sub_category').prop('disabled', true);
        //         }
        //     });
        // });
    </script>

@endsection
