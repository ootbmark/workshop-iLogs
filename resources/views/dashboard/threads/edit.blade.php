@extends('layouts.app')
@section('add-css')
    <link href="/css/crop.css" rel="stylesheet">
    <link href="{{ mix('css/profile.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('profile._sidebar')

    <div class="profile-content">
        @include('dashboard._navbar')

        <div class="discussions-container ml-0 mt-4" style="padding-bottom:100px">
            <form action="{{ route('dashboard.threads.update', $thread->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="subject">Subject<span class="text-red ml-1">*</span></label>
                            <input type="text" class="form-control" id="subject" placeholder="Subject" name="subject"
                                value="{{ $thread->subject }}">
                            @error('subject')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="body">Body<span class="text-red ml-1">*</span></label>
                            <textarea class="form-control" id="body" placeholder="Body" name="body">{{ $thread->body }}</textarea>
                            @error('body')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subject">Location<span class="text-red ml-1">*</span></label>
                            <input type="text" class="form-control" id="location" placeholder="Location" name="location"
                                value="{{ $thread->location }}">
                            @error('location')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="group_id">Group<span class="text-red ml-1">*</span></label>
                            <select name="group_id" id="group_id" class="form-control">
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" @if ($group->id == $thread->group_id) selected @endif>
                                        {{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id">Post as<span class="text-red ml-1">*</span></label>
                            <select name="user_id" id="user_id" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($user->id == $thread->user_id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status<span class="text-red ml-1">*</span></label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" @if ($thread->status == 'active') selected @endif>Active</option>
                                <option value="new" @if ($thread->status == 'new') selected @endif>New</option>
                                <option value="parked" @if ($thread->status == 'parked') selected @endif>Parked</option>
                                <option value="deleted" @if ($thread->status == 'deleted') selected @endif>Deleted</option>
                            </select>
                            @error('status')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="files">FILES</label>
                            <br>
                            <div class="form-group row">
                                <div class="col-md-9 col-lg-7">
                                    <input type="file" name="files[]" id="files">
                                </div>
                                <div class="col-md-9 col-lg-7 mt-2">
                                    <input type="file" name="files[]" id="files">
                                </div>
                                <div class="col-md-9 col-lg-7 mt-2">
                                    <input type="file" name="files[]" id="files">
                                </div>
                                <div class="col-md-9 col-lg-7 mt-2">
                                    <input type="file" name="files[]" id="files">
                                </div>
                                @error('files')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="font-16">
                                Supported formats : pdf, doc, docx, odt, ppt, pptx, pps, xls, xlsx, jpg, gif, png, txt
                                Please be advised that this form could upload a max. of 20MB (Max. size of individual file <
                                    5MB) While uploading files, don't close this window until you get a message back from
                                    the system. </p>
                                    <div>
                                        {!! NoCaptcha::display() !!}
                                        @error('g-recaptcha-response')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                        </div>
                        <div class="form-group">

                            <button type="submit" value="preview" name="submit"
                                class="btn my-btn btn-white text-uppercase mt-3">PREVIEW</button>
                            <button type="submit" value="submit" name="submit"
                                class="btn my-btn text-uppercase mt-3">Update</button>
                        </div>

                    </div>
                </div>
            </form>
            {!! NoCaptcha::renderJs() !!}
        </div>
    </div>
@endsection

@section('scripts')
    @include('profile._avatar_scripts')
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="/ckeditor/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#body'), {
                toolbar: {
                    removeItems: ['uploadImage']
                },
            })
            .catch(error => {
                console.error(error);
            });

        $('#group_id').select2();
        $('#user_id').select2();
    </script>
@endsection
