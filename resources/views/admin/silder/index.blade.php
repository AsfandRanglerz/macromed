@extends('admin.layout.app')
@section('title', 'Silder Images')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">

                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Upload Images</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('silders.upload-images') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="image">Upload Images</label>
                                                <input name="image[]" type="file" multiple class="form-control-file"
                                                    id="image" accept="image/jpeg, image/png, image/gif"
                                                    aria-describedby="imageHelpBlock">
                                                <small id="imageHelpBlock" class="form-text text-muted">
                                                    <ul class="text-danger">
                                                        <li>You must upload at least 3 images at a time.</li>
                                                        <li>You can upload multiple images.</li>
                                                        <li>Maximum file size: 1MB.</li>
                                                        <li>Allowed formats: JPEG, PNG, GIF.</li>
                                                    </ul>
                                                </small>
                                                @error('image.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary btn-block">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Images<small class="font-weight-bold"></small></h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center border border-1"
                                        id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Images</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($silderImages as $image)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <img src="{{ asset($image->images) }}" alt="Image"
                                                            style="max-width: 100px; max-height: 100px;">
                                                    </td>
                                                    <td>
                                                        <button id="button-{{ $image->id }}"
                                                            onclick="updateCoverStatus({{ $image->id }})"
                                                            class="btn {{ $image->status == '0' ? 'btn-success' : 'btn-danger' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-left">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="16" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>

                                                    </td>
                                                    <td>
                                                        <form action="{{ route('silderImage.delete', $image->id) }}"
                                                            method="GET">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        function updateCoverStatus(imageId) {
            $.ajax({
                url: '{{ route('silders.update-cover-status', ['imageId' => ':imageId']) }}'
                    .replace(':imageId', imageId),
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    // Reset all buttons to default (success)
                    toastr.success(response.message);
                    $('.btn').removeClass('btn-danger').addClass('btn-success');
                    var button = $('#button-' + imageId);
                    var svgIcon = button.find('svg');
                    if (response.alert && response.alert === 'success') {
                        button.removeClass('btn-success').addClass('btn-danger');
                        svgIcon.removeClass('feather-toggle-left').addClass('feather-toggle-right');
                    } else {
                        button.removeClass('btn-danger').addClass('btn-success');
                        svgIcon.removeClass('feather-toggle-right').addClass('feather-toggle-left');
                    }
                    // window.location.reload();
                },
                error: function(xhr, status, error) {
                    toastr.error(error);
                    console.log(xhr.responseText);
                }
            });
        }
    </script>


@endsection
