@extends('admin.layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('admin/dropify/css/dropify.min.css')}}">
<link href="{{ asset('admin') }}/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
               <h3><a>Settings </a>/ Contact Details</h3> 
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <h4>Contact Details</h4>
                    <form method="post" action="{{ route('admin.app.contact.details.create') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">App Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ $data->email }}" required>
                                </div>
                            </div>
                            <input type="hidden" name="id" class="form-control" value="{{ $data->id }}" required>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">App Phone </label>
                                    <input type="text" name="phone" class="form-control" value="{{ $data->phone }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                            <div class="col-md-12" id="errosMsg"></div>
                        </div>
                        
                </form>

            </div>
        </div>

    </div>
</div>
</div>




@endsection
@section('js')

@endsection