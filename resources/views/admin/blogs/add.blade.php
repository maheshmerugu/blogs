@extends('admin.layouts.app')

@section('css')
    <!-- Include your CSS stylesheets here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <style>
        /* Add any additional styling here */
    </style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="{{ route('admin.blogs.list') }}"> Blog List </a>/ Add Blog</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="offset-md-2 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create Blog</h4>
                                    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="create-blog">
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
                                                    @foreach($categories as $category)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category['category_id'] }}">
                                                            <label class="form-check-label">{{ $category['name'] }}</label>
                                                        </div>
                                                    @endforeach
                                                    @error('categories[]')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="tags">Tags</label>
                                                    <input type="text" name="tags" class="form-control" id="tags" placeholder="e.g., ['Tag1', 'Tag2']">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="images">Images</label>
                                                    <input type="file" name="images[]" class="form-control" id="images" multiple>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3 text-center">
                                                <button class="btn btn-md btn-primary" type="submit">Submit</button>
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
<script src="https://cdn.tiny.cloud/1/base64:FLvNzyJN+jMnRdhxGcHIgkcrgKDHbp3HA25NsbDPc0c=/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    $(document).ready(function(){
        tinymce.init({
            selector: '#description',
            plugins: 'autolink lists link image charmap print preview hr anchor fontsize',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect',
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        });

        $('#create-blog').on('submit', function(e){
            e.preventDefault();

            // Destroy TinyMCE instance before submitting the form
            tinymce.get('description').destroy();

            let fd = new FormData(this);

            $(".loader").show();

            $.ajax({
                url: "{{ route('admin.blogs.store') }}",
                type: 'POST',
                dataType: 'json',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".loader").hide();

                    console.log(data);

                    if (data.status) {
                        $('.errorMsg').html(`<div class="alert alert-success">${data.message}</div>`);

                        setTimeout(() => {
                            window.location.href = "{{ route('admin.blogs.list.view') }}";
                        }, 3000);
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
