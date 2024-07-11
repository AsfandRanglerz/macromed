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
                                    <thead class="text-center">
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
                                    <tbody class="text-center">
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
                            return '<a href="' +
                                "{{ route('product.edit', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-success mb-1 mr-1  text-white"><i class="fas fa-edit"></i></a>' +
                                '<button class="btn btn-danger mb-1 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('.table').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });
        });


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
    </script>

@endsection
