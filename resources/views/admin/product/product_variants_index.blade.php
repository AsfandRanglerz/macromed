@extends('admin.layout.app')
@section('title', 'Product')
@section('content')
    <!-- Delete Subadmin Modal -->
    <div class="modal fade" id="deleteSubadminModal" tabindex="-1" role="dialog" aria-labelledby="deleteSubadminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubadminModalLabel">Delete Product Variant </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this product variant ?</h5>
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
                                    <h4>Product Varaints</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead class="text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>SKU</th>
                                            <th>Packing</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price per Unit</th>
                                            <th>Selling Price per Unit</th>
                                            <th>Actual Weight</th>
                                            <th>Shipping Weight</th>
                                            <th>Shipping Chargeable Weight</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            {{-- <th>Description</th> --}}
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
        $(document).ready(function() {
            function reloadDataTable() {
                var dataTable = $('#example').DataTable();
                dataTable.ajax.reload();
            }

            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('product.variants', ['id' => $id]) }}",
                    "type": "GET",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    },
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1; // Display row index
                        }
                    },
                    {
                        "data": "s_k_u"
                    },
                    {
                        "data": "packing"
                    },
                    {
                        "data": "unit"
                    },
                    {
                        "data": "quantity"
                    },
                    {
                        "data": "price_per_unit"
                    },
                    {
                        "data": "selling_price_per_unit"
                    },
                    {
                        "data": "actual_weight"
                    },
                    {
                        "data": "shipping_weight"
                    },
                    {
                        "data": "shipping_chargeable_weight"
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
                            return '<button class="btn btn-danger mb-1 mr-1 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                    // { "data": "description" }
                ]
            });

            // Event listener for delete button
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var subadminId = $(this).data('id');
                deleteSubadminModal(subadminId);
            });

            // Update status event listener
            $('#example').on('click', '#update-status', function() {
                var button = $(this);
                var userId = button.data('userid');
                var currentStatus = button.text().trim().toLowerCase();
                var newStatus = currentStatus === 'active' ? '1' : '0';
                button.prop('disabled', true);

                $.ajax({
                    url: '{{ route('variantsBlock.update', ['id' => ':userId']) }}'.replace(
                        ':userId', userId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Update button text and class
                        var buttonText = newStatus === '1' ? 'Active' : 'Inactive';
                        var buttonClass = newStatus === '1' ? 'btn-success' : 'btn-danger';
                        button.text(buttonText).removeClass('btn-success btn-danger').addClass(
                            buttonClass);
                        // Update status cell content
                        var statusCell = button.closest('tr').find('td:eq(6)');
                        var statusText, statusClass;
                        statusCell.html('<span class="' + statusClass + '">' + statusText +
                            '</span>');
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

            // Delete Subadmin function
            function deleteSubadminModal(subadminId) {
                $('#confirmDeleteSubadmin').data('subadmin-id', subadminId);
                $('#deleteSubadminModal').modal('show');
            }

            $('#confirmDeleteSubadmin').click(function() {
                var subadminId = $(this).data('subadmin-id');
                deleteSubadmin(subadminId);
            });

            function deleteSubadmin(subadminId) {
                $.ajax({
                    url: "{{ route('variant.delete', ['id' => ':subadminId']) }}".replace(':subadminId',
                        subadminId),
                    type: 'GET',
                    success: function(response) {
                        toastr.success('Product Variant Deleted Successfully!');
                        $('#deleteSubadminModal').modal('hide');
                        reloadDataTable();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    </script>

@endsection
