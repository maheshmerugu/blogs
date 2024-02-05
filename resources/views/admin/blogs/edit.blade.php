@extends('admin.layouts.app')

@section('css')
    <!-- Include your CSS stylesheets here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin/dropify/css/dropify.min.css') }}">
    <link href="{{ asset('admin') }}/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Edit Blog</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="offset-md-2 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Edit Blog</h4>
                                <form action="{{ route('admin.blogs.update', ['blog' => $blog->id]) }}" method="POST" enctype="multipart/form-data" id="edit-blog">
                                    @csrf
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
                                                <textarea name="description" id="description" class="form-control" rows="5">{{ $blog->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <select name="category" class="form-control">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $blog->blog_category->category->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="tags">Tags</label>
                                                <input type="text" name="tags" class="form-control" id="tags" value="{{ implode(',', $blog->tags->pluck('name')->toArray()) }}" placeholder="e.g., 'Tag1,Tag2'">
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="images">Images</label>
                                                <input type="file" name="images[]" class="form-control" id="images" multiple>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-3 text-center">
                                            <button class="btn btn-md btn-primary" type="submit" id="submitButton">Update</button>
                                        </div>
                                        <div class="col-md-12 errorMsg"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
            tokenSeparators: [', ', ' '],
        });

        $('#edit-blog').on('submit', function(e){
            e.preventDefault();

            // Destroy TinyMCE instance before submitting the form
            tinymce.get('description').destroy();

            let fd = new FormData(this);

            $(".loader").show();

            $.ajax({
                url: $(this).attr('action'), 
                type: 'POST', 
                dataType: 'json',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".loader").hide();

                    if (data.status) {
                        // Redirect to the blog list page
                        window.location.href = "{{ route('admin.blogs.list.view') }}";
                    } else {
                        $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
