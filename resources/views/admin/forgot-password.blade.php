<!DOCTYPE html>
<html>

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
            @if (Session::has('message'))
            <div class="card text-success">
                <div class="card-body">
                    <h3>{{ Session::get('message') }}</h3>
                    <a v href="{{ route('admin.login') }}">Login</a>
                </div>

            </div>
            @else
            <p>Reset your password</p>
            <form class="m-t" role="form" action="{{ route('admin.password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email" class="col-form-label text-md-end">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="col-form-label text-md-end">{{ __('Password') }}</label>


                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-form-label text-md-end">{{ __('Confirm
                                                                                                                                                                                                                                                                                                                                        Password') }}</label>


                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>

            </form>
            <div>
                <a href="{{ url('/') }}"> <i class="fa fa-arrow-circle-o-left"></i> Back</a>
            </div>
            @endif

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>

</body>

</html>