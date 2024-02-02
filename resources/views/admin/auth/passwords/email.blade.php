<!DOCTYPE html>
<html>

<!-- <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

<!-- CSRF Token -->
<!-- <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/logo.svg') }}" />


</head> -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Motion')}}</title>
    @include('admin.layouts.partials.css')

</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div style="margin-top: 100px">
                <img src="{{ asset('website') }}/images/university-logo.png" style="max-width: 300px; max-hight: 400px; " />
            <h3>Admin</h3>
            </div>
            </p>
            <p>Reset your password</p>
            <form class="m-t" role="form" action="{{ route('admin.reset.password.link') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="col-form-label text-md-end">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('status') }}</strong>
                    </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>

            </form>
            <div>
                <a href="{{ url('/login') }}"> <i class="fa fa-arrow-circle-o-left"></i> Back</a>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>

</body>

</html>