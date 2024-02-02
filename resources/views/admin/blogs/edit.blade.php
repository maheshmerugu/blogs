@extends('admin.layouts.app')

@section('css')
    <!-- Include your CSS stylesheets here -->
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
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
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

            $(".loader").show();

            $.ajax({
                url: "{{ route('admin.blogs.update', ['blog' => $blog->id]) }}",
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
                            window.location.href = "{{ route('admin.blogs.list') }}";
                        }, 3000);
                    } else {
                        $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`);
                    }
                }
            });
        });
    });
</script>
@endsection
