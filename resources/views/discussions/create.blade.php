@extends('layouts.app')

@section('add-css')
    <style>
        .section-right {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .donation {
            margin-bottom: 50px;
        }
    </style>
@endsection

@section('content')

    <div class="my-container pt-5">
        <div class="main-section d-flex flex-wrap pb-4 forum-section">

            <div class="section-left">
                <form class="login-form" method="POST" action="{{ route('discussions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <h1 class="title-h2 mb-4 text-center">START NEW DISCUSSION</h1>

                    <div class="login-container bg-white">
                        <h3 class="title-h3 mb-4">YOUR QUESTION</h3>

                        <div class="form-group row">
                            <label for="subject" class="col-md-3 col-form-label text-uppercase">SUBJECT<span
                                    class="text-red ml-1">*</span></label>
                            <div class="col-md-9 col-lg-7">
                                <input type="text" class="form-control" id="subject" name="subject"
                                    value="{{ $subject ?: $thread->subject ?? old('subject') }}" placeholder="Subject">
                                @error('subject')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="body" class="col-md-3 col-form-label text-uppercase">QUESTION<span
                                    class="text-red ml-1">*</span></label>
                            <div class="col-md-9 col-lg-7">
                                <textarea name="body" id="editor">{{ $thread->body ?? old('body') }}</textarea>
                                @error('body')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location" class="col-md-3 col-form-label text-uppercase">LOCATION<span
                                    class="text-red ml-1">*</span></label>
                            <div class="col-md-9 col-lg-7">
                                <input type="text" class="form-control" id="location" name="location"
                                    value="{{ $thread->location ?? old('location') }}" placeholder="Current location">
                                @error('location')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="group_id" class="col-md-3 col-form-label text-uppercase">GROUP<span
                                    class="text-red ml-1">*</span></label>
                            <div class="col-md-9 col-lg-7">
                                <select name="group_id" id="group_id">
                                    <option value="">Choose</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" data-type="{{ $group->type }}"
                                            @if (($thread && $thread->group_id == $group->id) || old('group_id') == $group->id) selected @endif>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if (Auth::user()->role == 'admin')
                            <div class="form-group row">
                                <label for="user_id" class="col-md-3 col-form-label text-uppercase">POST AS<span
                                        class="text-red ml-1">*</span></label>
                                <div class="col-md-9 col-lg-7">
                                    <select name="user_id" id="user_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" data-type="{{ $user->type }}"
                                                @if (($thread && $thread->user_id == $user->id) || Auth::id() == $user->id) selected @endif>{{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="files" class="col-md-3 col-form-label text-uppercase pb-0"
                                style="line-height: 1">FILES</label>
                            <div class="col-md-9 col-lg-7">
                                <input type="file" name="files[]" id="files">
                            </div>
                            <label for="files" class="col-md-3 col-form-label text-uppercase"></label>
                            <div class="col-md-9 col-lg-7 mt-2">
                                <input type="file" name="files[]" id="files">
                            </div>
                            <label for="files" class="col-md-3 col-form-label text-uppercase"></label>
                            <div class="col-md-9 col-lg-7 mt-2">
                                <input type="file" name="files[]" id="files">
                            </div>
                            <label for="files" class="col-md-3 col-form-label text-uppercase"></label>
                            <div class="col-md-9 col-lg-7 mt-2">
                                <input type="file" name="files[]" id="files">
                                @error('files')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group">
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
                        <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group">

                            <button type="submit" value="preview" name="submit"
                                class="btn my-btn btn-white text-uppercase mt-3">PREVIEW</button>
                            <button type="submit" value="submit" name="submit"
                                class="btn my-btn text-uppercase mt-3 disabled-after-submit">Submit</button>
                        </div>

                    </div>

                </form>
                {!! NoCaptcha::renderJs() !!}
            </div>
            <div class="section-right">
                <div class="donation">
                    @include('layouts.parts._donation')
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="/ckeditor/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: {
                    removeItems: ['uploadImage']
                },
            })
            .catch(error => {
                console.error(error);
            });

        $('#group_id').select2();
        $('#user_id').select2();

        $("#files").on("change", function() {
            if ($("#files")[0].files.length > 4) {
                $("#files").val('');
                alert("You can select only 4 files");

            }
        });

        $('[name="files[]"]').change(function() {
            if (this.files[0].size > (5 * 1024 * 1024)) {
                $(this).val('');
                alert('Sorry, your file size is too large. Please upload a maximum of 5 MB')
            }
        })
    </script>
@endsection
