@extends('admin.layout.app')
@section('title', 'Career Section')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form action="{{ route('careersection.save', ['id' => $careerSections->id]) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Blog</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control long_description">{{ $careerSections->description }}</textarea>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary mr-1" type="submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection
@section('js')
    <script>
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
    </script>
@endsection
