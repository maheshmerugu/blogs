@extends('admin.layouts.app')

@section('css')
    <!-- Add your CSS links or styles here -->
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Edit Category</h3>
                </div>
                <div class="ibox-content">
                    <form id="updateCategoryForm" data-id="{{ $category->id }}" action="{{ route('admin.category.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                </div>
                            </div>
                            <!-- Add more fields as needed -->

                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="updateCategoryBtn">Update Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Update Category button click event
            $('#updateCategoryBtn').on('click', function () {
                var formData = $('#updateCategoryForm').serialize();

                $.ajax({
                    url: "{{ route('admin.category.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function (data) {
                        if (data.status) {
                            // Update successful, redirect to the list view
                            window.location.href = "{{ route('admin.categories.list.view') }}";
                        } else {
                            // Update failed, you can handle error actions here
                            alert(data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle Ajax errors here
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <!-- Add your additional JS scripts or links here -->
@endsection

