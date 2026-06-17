@extends('layouts.appV2')

@section('content')
    {{-- <div class="my-container pt-5">
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
                    <a href="{{ route('auth.redirect', 'linkedin') }}" class="ml-2 btn my-btn btn-white">Login with
                        Linkedin</a>
                </div>

                <div class="col-md-9 col-lg-7 offset-md-3 pl-md-2 pl-0 pr-0 text-right">
                    <a href="{{ route('password.request') }}"
                        class="link-3 mr-sm-5 mr-2 mb-3 no-wrap d-inline-block">Forgotten password</a>
                    <a href="{{ route('register') }}" class="link-3 mb-3 no-wrap d-inline-block">Create an account</a>
                </div>
            </form>
            {!! NoCaptcha::renderJs() !!}
        </div>
    </div> --}}
    <section id="view-login" class="container py-5 d-flex align-items-center justify-content-center"
        style="min-height: calc(100vh - 100px);">
        <div class="card-custom p-4 p-md-5 shadow-lg position-relative overflow-hidden"
            style="max-width: 450px; width: 100%;">
            <div class="position-absolute top-0 start-0 w-100"
                style="height: 6px; background: linear-gradient(to right, var(--bs-navy-900), var(--bs-spreadBlue-500), var(--bs-spreadOrange-500));">
            </div>

            <div class="text-center mb-4">
                <div class="bg-navy-50 text-navy-900 p-3 rounded-circle d-inline-flex border mb-3">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h2 class="fw-extrabold text-dark tracking-tight">Welcome to i-Logs</h2>
                <p class="text-muted small">Enter your credentials to access the Workshop Evaluation System</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 text-start">
                    <label for="login-email" class="form-label text-uppercase text-muted fw-bold small tracking-wider"
                        style="font-size: 11px;">Email Address / Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i
                                class="bi bi-envelope-fill"></i></span>
                        <input id="login-email" type="text" name="email" value="{{ old('email') }}"
                            class="form-control border-start-0 py-2.5 shadow-none" placeholder="e.g. admin@wes.com">

                    </div>
                    @error('email')
                        <small class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
                <div class="mb-4 text-start">
                    <label for="login-password" class="form-label text-uppercase text-muted fw-bold small tracking-wider"
                        style="font-size: 11px;">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i
                                class="bi bi-key-fill"></i></span>
                        <input id="login-password" type="password" name="password"
                            class="form-control border-start-0 border-end-0 py-2.5 shadow-none" placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('login-password', this)"
                            class="input-group-text bg-white text-muted border-start-0"><i
                                class="bi bi-eye-slash-fill"></i></button>
                    </div>
                    @error('password')
                        <small class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-navy-900 w-100 py-3 rounded-3 shadow-sm fw-bold">
                    Sign In
                </button>
            </form>
        </div>
    </section>
@endsection
