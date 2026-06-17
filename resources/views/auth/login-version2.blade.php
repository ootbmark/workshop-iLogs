@extends('layouts.appV2')

@section('content')
    <div class="my-container pt-5">
        <h1 class="title-h2 mb-4 text-center">LOGIN WITH MY-SPREAD</h1>

        <div class="login-container bg-white">
            <h3 class="title-h3">ACCOUNT DETAILS</h3>

            <form class="mt-4 login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-uppercase">EMAIL OR USERNAME<span
                            class="text-red ml-1">*</span></label>
                    <div class="col-md-9 col-lg-7">
                        <input type="text" name="email" class="form-control" id="email" value="{{ old('email') }}"
                            placeholder="Email or Username">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('username')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-3 col-form-label text-uppercase">PASSWORD<span
                            class="text-red ml-1">*</span></label>
                    <div class="col-md-9 col-lg-7">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group pl-md-2">
                    <div class="custom-control custom-checkbox col-md-9 offset-md-3">
                        <input type="checkbox" class="custom-control-input" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group d-flex">
                    <div>
                        {!! NoCaptcha::display() !!}
                    </div>
                    <br>

                </div>
                <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group d-flex">
                    <button type="submit" class="btn my-btn text-uppercase">Submit</button>
                    {{--                <a href="{{route('auth.redirect', 'linkedin')}}" class="ml-2 btn my-btn btn-white">Login with Linkedin</a> --}}
                </div>

                <div class="col-md-9 col-lg-7 offset-md-3 pl-md-2 pl-0 pr-0 text-right">
                    <a href="{{ route('password.request') }}"
                        class="link-3 mr-sm-5 mr-2 mb-3 no-wrap d-inline-block">Forgotten password</a>
                    <a href="{{ route('register') }}" class="link-3 mb-3 no-wrap d-inline-block">Create an account</a>
                </div>
            </form>
            {!! NoCaptcha::renderJs() !!}
        </div>
    </div>
@endsection
