
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Login - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="Login to Application">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <link href="{{ asset('assets/styles/pro.css') }}" rel="stylesheet"></head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="app-logo-inverse mx-auto mb-3"></div>
                    <div class="modal-dialog w-100 mx-auto">
                        <form method="POST" action="{{ route('login') }}" class="">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            {{--<div>Welcome back,</div>--}}
                                            <span>Please sign in to your account below.</span>
                                        </h4>
                                    </div>


                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <input name="login" id="login" value="{{ old('username') ?: old('email') }}" placeholder="{{ __('Username or E-Mail') }}" type="text" class="form-control {{ $errors->has('email') || $errors->has('username') ? 'is-invalid' : '' }}" required>
                                                    @if ($errors->has('username') || $errors->has('email'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <input name="password" id="password" placeholder="{{ __('Password') }}" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-relative form-check">
                                            <input name="remember" id="remember" type="checkbox" value="{{ old('remember') }}" class="form-check-input">
                                            <label for="remember" class="form-check-label">{{ __('Keep me logged in') }}</label>
                                        </div>

    {{--                                <div class="divider"></div>--}}
    {{--                                <h6 class="mb-0">No account? <a href="javascript:void(0);" class="text-primary">Sign up now</a></h6>--}}
                                </div>
                                <div class="modal-footer clearfix">
    {{--                                <div class="float-left">--}}
    {{--                                    <a href="javascript:void(0);" class="btn-lg btn btn-link">Recover Password</a>--}}
    {{--                                </div>--}}
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-primary btn-lg">{{ __('Login to App') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Designed & Developed with <i style="color: darkred;" class="fa fa-heart"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/scripts/main.cba69814a806ecc7945a.js') }}"></script></body>
</html>
