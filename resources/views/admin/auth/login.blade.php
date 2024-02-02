<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <link rel="shortcut icon" href="{{ asset('icon.png') }}" />

    <!-- CSRF Token -->
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>Auricle | Admin</title>

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">
    <div id="app">      
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div class="login_section">
                <div style="margin-top: 100px">
                    <img src="{{ asset('website') }}/images/auricle_logo.png" style="max-width: 300px; max-hight: 400px; " />
                </div>
                <!-- <h3> {{ config('app.name', 'Motion') }}</h3> -->
                <p>Login in. To see it in action.</p>

                <form class="m-t" role="form" method="POST" action="{{route('admin.login.submit')}}">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Username" required  autocomplete="email" autofocus>

                        @error('email')
                            <p class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                        <a href="javascript:;" onclick="showHide()" class="show-hide">
                            <i id="show_hide_icon" class="fa fa-eye-slash"></i>
                        </a>
                        @error('password')
                            <p class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember') }}
                            </label>
                        </div>
                    </div> --}}

                    @error('error')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                    @enderror

                    <button type="submit" class="btn btn-primary block full-width m-b">{{ __('Login') }}</button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('admin.reset.password') }}">
                            <small>{{ __('Forgot Your Password?') }}</small>
                        </a>
                    @endif
                </form>
                <!-- <p class="m-t"> <small>{{ config('app.name', 'Motion') }} &copy; 2022-23</small> </p> -->
            </div>
        </div>
    </div>

    <script>
        function showHide()
        {
            let e = document.getElementById('show_hide_icon');
            if(document.getElementById('password').getAttribute('type') == 'password')
            {
                document.getElementById('password').setAttribute('type', 'text');
                e.classList.remove('fa-eye-slash');
                e.classList.add('fa-eye');
            }
            else
            {
                document.getElementById('password').setAttribute('type', 'password');
                e.classList.remove('fa-eye');
                e.classList.add('fa-eye-slash');
            }
        }
    </script>

</body>
</html>
