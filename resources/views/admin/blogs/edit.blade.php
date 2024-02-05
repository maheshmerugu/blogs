@extends('admin.layouts.app')

@section('css')
    <!-- Include your CSS stylesheets here -->
<<<<<<< HEAD
@endsection

@section('content')

=======
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin/dropify/css/dropify.min.css') }}">
    <link href="{{ asset('admin') }}/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
@endsection

@section('content')
>>>>>>> origin/venkatesh
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
<<<<<<< HEAD
                <h3><a href="{{ route('admin.blogs.list') }}"> Blog List </a>/ Edit Blog</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="offset-md-2 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Edit Blog</h4>
                                    <form action="{{ route('admin.blogs.update', ['blog' => $blog->id]) }}" method="POST" enctype="multipart/form-data" id="edit-blog">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" class="form-control" id="title" value="{{ $blog->title }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" class="form-control" id="description" rows="5" required>{{ $blog->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="categories">Categories (JSON)</label>
                                                    <input type="text" name="categories" class="form-control" id="categories" value="{{ json_encode($blog->categories) }}" placeholder="e.g., ['Category1', 'Category2']">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="tags">Tags (JSON)</label>
                                                    <input type="text" name="tags" class="form-control" id="tags" value="{{ json_encode($blog->tags) }}" placeholder="e.g., ['Tag1', 'Tag2']">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="images">Images (JSON)</label>
                                                    <input type="text" name="images" class="form-control" id="images" value="{{ $blog->images }}" placeholder="e.g., ['image1.jpg', 'image2.png']">
                                                </div>
                                            </div>
                                            <!-- Other fields you may want to add -->
                                            <div class="col-md-12 mt-3 text-center">
                                                <button class="btn btn-md btn-primary" type="submit">Update</button>
                                            </div>
                                            <div class="col-md-12 errorMsg"></div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        
=======
                <h3><a href="{{ route('admin.blogs.list.view') }}"> Blog List </a>/ Add Blog</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="offset-md-2 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create Blog</h4>
                                <form action="{{ route('admin.blogs.create') }}" method="POST" enctype="multipart/form-data" id="create-blog">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" class="form-control" id="title" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="categories">Categories</label>
                                                <select name="categories[]" class="form-control" multiple>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('categories')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="tags">Tags</label>
                                                <select name="tags[]" class="form-control" multiple>
                                                    <!-- You may preload existing tags here if needed -->
                                                </select>
                                                @error('tags')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="images">Images</label>
                                                <input type="file" name="images[]" class="form-control" id="images" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3 text-center">
                                            <button class="btn btn-md btn-primary" type="submit" id="submitButton">Submit</button>
                                        </div>
                                        <div class="col-md-12 errorMsg"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
>>>>>>> origin/venkatesh
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<<<<<<< HEAD

@endsection

@section('js')
<script>
    $(document).ready(function(){
   
        $('#edit-blog').on('submit', function(e){

            e.preventDefault();

            let fd = new FormData(this);

            // Convert images array to JSON string
            let imagesArray = fd.get('images');
            fd.set('images', JSON.stringify(imagesArray));
=======
@endsection

@section('js')
<script src="https://cdn.tiny.cloud/1/e2o5xoxb332zatu4vp439fgtey15kr1lirt4t6lhvwwhbya9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        tinymce.init({
            selector: '#description',
            plugins: 'autolink lists link image charmap print preview hr anchor fontsize',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect',
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        });

        // Initialize select2 for categories
        $('select[name="categories\[\]"]').select2();

        // Initialize select2 for tags
        $('select[name="tags\[\]"]').select2({
            tags: true,
            tokenSeparators: [',', ' '],
        });

        $('#create-blog').on('submit', function(e){
            e.preventDefault();

            // Destroy TinyMCE instance before submitting the form
            tinymce.get('description').destroy();

            let fd = new FormData(this);
>>>>>>> origin/venkatesh

            $(".loader").show();

            $.ajax({
<<<<<<< HEAD
                url: "{{ route('admin.blogs.update', ['blog' => $blog->id]) }}",
=======
                url: "{{ route('admin.blogs.create') }}",
>>>>>>> origin/venkatesh
                type: 'POST',
                dataType: 'json',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".loader").hide();

<<<<<<< HEAD
                    console.log(data);

                    if (data.status) {
                        $('.errorMsg').html(`<div class="alert alert-success">${data.message}</div>`);

                        setTimeout(() => {
                            window.location.href = "{{ route('admin.blogs.list') }}";
                        }, 3000);
                    } else {
                        $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`);
                    }
=======
                    if (data.status) {
                        // Redirect to the blog list page
                        window.location.href = "{{ route('admin.blogs.list.view') }}";
                    } else {
                        $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
>>>>>>> origin/venkatesh
                }
            });
        });
    });
</script>
@endsection
