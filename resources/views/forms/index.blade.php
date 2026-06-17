@extends('forms.app')

{{-- @section('head')
    <link href="{{ asset('/css/quiz.css') }}" rel="stylesheet">
@endsection --}}

@section('content')
    <div class="qr-wrapper">
        <div class="qr-card">
            <div class="text-center py-4">
                <div class="mb-4 text-primary">
                    <i class="bi bi-shield-lock" style="font-size: 3.5rem; color: #3f51b5;"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">Verification Security Entrance</h3>
                <p class="text-muted mb-4 mx-auto" style="max-width: 480px;">
                    To begin, please input the 6-character unique access authorization key provided during workshop
                    attendance.
                </p>

                <form id="form-step-1" action="{{ route('form.verifyCode') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" id="access-code-input" class="form-control code-input" placeholder="EX:FD123E"
                            name="formCode" maxlength="6" autocomplete="off">
                        @error('formCode')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-spread-submit px-5">
                        NEXT
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- <div class="my-container pt-5">
        <h1 class="title-h2 mb-4 text-center">WORKSHOP SURVEY</h1>

        <div class="login-container bg-white">

            <form class="mt-4 login-form" action="{{ route('form.verifyCode') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-uppercase">WORKSHOP FORM CODE<span
                            class="text-red ml-1">*</span></label>
                    <div class="col-md-9 col-lg-7">
                        <input type="text" name="formCode" class="form-control" id="formCode"
                            value="{{ old('formCode') }}" placeholder="Workshop Code">
                        @error('formCode')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group d-flex">
                    <button type="submit" class="btn my-btn text-uppercase">Submit</button>
                  
                </div>
            </form>

        </div>
    </div> --}}
@endsection
