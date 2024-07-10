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
                                            <th>Variants</th>
                                            <th>Product Name</th>
                                            <th>Brands</th>
                                            <th>Certifications</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Company</th>
                                            <th>Models</th>
                                            <th>Status</th>
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
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('product_variant.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary text-white"><i class="fas fa-store"></i></a>';
                        },
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
                        "data": "product_category_sub_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return 'No data found!';
                            }
                            return data.map(productCategory => productCategory.categories.name)
                                .join(', ');
                        }
                    },
                    {
                        "data": "product_category_sub_category",
                        "render": function(data, type, row) {
                            if (data.length === 0) {
                                return 'No data found!';
                            }
                            let subcategories = [];
                            data.forEach(item => {
                                if (item.categories && item.categories.subcategories) {
                                    item.categories.subcategories.forEach(subcategory => {
                                        subcategories.push(subcategory.name);
                                    });
                                }
                            });
                            return subcategories.length ? subcategories.join(', ') :
                                'No subcategories found!';
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
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-1 mr-2 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-1 mr-2 text-white deleteSubadminBtn" data-id="' +
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
                var id = $(this).data('id');
                deleteModelsModal(id);
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
    </script>

@endsection
