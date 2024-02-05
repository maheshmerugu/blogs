@extends('admin.layouts.app')

@section('css')
    <!-- Include your CSS stylesheets here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin/dropify/css/dropify.min.css') }}">
    <link href="{{ asset('admin') }}/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="{{ asset('admin') }}/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Blogs</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">
                        <!-- Add any additional content here -->
                    </div>
                    <div class="col-md-2 float-right">
                        <a href="{{ route('admin.blogs.add') }}">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Blog
                            </button>
                        </a>
                    </div>
                </div>
              @livewire('admin.blogs')
            </div>
        </div>
    </div>
</div>

   
@endsection

@section('js')
@livewireScripts()
@endsection

