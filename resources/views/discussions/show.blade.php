@extends('layouts.app')

@section('add-css')
    <style>
        .donation-separator {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .banner-ads {
            margin-top: 10px;
            margin-bottom: 30px;
            background-color: rebeccapurple;
            border-radius: 5px;
            color: aliceblue;
            text-align: center;
            font-weight: 900;
        }

        .banner-ads p {
            padding-top: 10px;
        }

        .banner-ads img {
            margin-top: 0px;
            width: 100%
        }
    </style>
@endsection

@section('content')

    <div class="my-container bg-white pt-3 mt-4 mb-5">

        <div class="breadcrumbs_header mb-5">
            <a href="{{ route('discussions.index') }}">Discussion Forum</a> &gt;
            <a href="{{ route('groups.discussions', $thread->group_id) }}">{{ $thread->group->name }}</a> &gt;
            {{ $thread->subject }}
        </div>
        <div class="" style="margin-bottom:5px;">
            <a href="https://rp-squared.com/" target="_blank">
                <img decoding="async" style="width: 100%;" src="{{ asset('images/rp-squared-780-x-90-banner_1.png') }}"
                    alt="spot_img">
            </a>
        </div>
        <div class="main-section d-flex flex-wrap pb-4 forum-section">

            <div class="section-left">
                <h2 class="title-h1 font-medium ">{{ $thread->subject }}</h2>
                <span class="mb-4"><span
                        class="badge bg-secondary text-white mb-4">{{ $thread->created_at->format('d F Y') }}</span></span>
                @auth
                    <div class="forum-change-links d-flex align-items-center mb-5">
                        @if (auth()->user()->role == 'admin')
                            @if (!$thread->is_closed)
                                <a href="#" data-toggle="modal" data-target="#lockModal"><i
                                        class="fa fa-lock"></i>Lock</a>
                            @else
                                <a href="#" onclick="$(this).next().submit()"><i class="fa fa-unlock"></i>Unlock</a>
                                <form action="{{ route('dashboard.threads.open', $thread->id) }}" method="POST"
                                    onsubmit="return confirm('Are You Sure?')">
                                    @csrf
                                </form>
                            @endif
                            @if ($thread->status != 'parked')
                                <a href="#" data-toggle="modal" data-target="#parkModal"><i
                                        class="fa fa-comments-o"></i>Park</a>
                            @else
                                <a href="#" onclick="$(this).next().submit()"><i class="fa fa-comments-o"></i>Remove from
                                    parked</a>
                                <form action="{{ route('dashboard.threads.unpark', $thread->id) }}" method="POST"
                                    onsubmit="return confirm('Are You Sure?')">
                                    @csrf
                                </form>
                            @endif
                            <a href="{{ route('dashboard.threads.edit', $thread->id) }}"><i class="fa fa-pencil"></i>Edit</a>
                            @if ($thread->status != 'deleted')
                                <a href="#" onclick="$(this).next().submit()"><i class="fa fa-trash"></i>Remove</a>
                                <form action="{{ route('dashboard.threads.destroy', $thread->id) }}" method="POST"
                                    onsubmit="return confirm('Are You Sure?')">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endif
                        @if (auth()->user()->favorites->contains($thread->id))
                            <a href="#" onclick="$(this).next().submit()"><i class="fa fa-heart"></i>Remove from
                                favorites</a>
                            <form action="{{ route('threads.favorite', $thread->id) }}" method="POST">
                                @csrf
                            </form>
                        @else
                            <a href="#" onclick="$(this).next().submit()"><i class="fa fa-heart-o"></i>Favorite</a>
                            <form action="{{ route('threads.favorite', $thread->id) }}" method="POST">
                                @csrf
                            </form>
                        @endif
                        <a href="#" data-toggle="modal" data-target="#shareModal"><i class="fa fa-share"></i>Share</a>
                    </div>

                @endauth

                {!! $thread->body !!}
                @if (count($thread->files))
                    <h5 class="mt-5 font-medium">Documents uploaded by user:</h5>
                    @foreach ($thread->files as $file)
                        <p><a href="{{ $file->path }}" target="_blank" class="link-black"><i class="fa fa-paperclip"></i>
                                {{ $file->name }}</a></p>
                    @endforeach
                @endif
                <h6 class="title-h6 font-medium mt-3 pt-5 mb-0">{{ $thread->replies()->active()->count() }} Answer(s)</h6>
                @auth
                    @foreach ($replies as $item)
                        <div class="d-flex flex-wrap align-items-start border-bottom pb-5 mt-2"
                            id="resonse{{ $item->id }}">
                            @auth
                                @if (auth()->user()->role == 'admin')
                                    <div class="w-100 mt-2 mb-md-5 mb-3">
                                        <div class="forum-change-links d-flex align-items-center">
                                            <a href="{{ route('dashboard.replies.edit', $item->id) }}"><i
                                                    class="fa fa-pencil"></i>Edit</a>
                                            <a href="#" onclick="$(this).next().submit()"><i
                                                    class="fa fa-comments-o"></i>Park</a>
                                            <form action="{{ route('dashboard.replies.park', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Are You Sure?')">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                            <div class="answer-user">
                                <figure>
                                    <img src="{{ $item->user->image }}" alt="{{ $item->user->name }}" class="img-fluid">
                                    <figcaption>
                                        <h4 class="font-medium"><a
                                                href="{{ route('users.show', $item->user_id) }}"></a>{{ $item->user->name }}
                                        </h4>
                                        <p>{{ $item->user->job_title }}</p>
                                        <p><a
                                                href="{{ route('organisations.show', $item->user->organisation_id) }}">{{ $item->user->organisation->name }}</a>
                                        </p>
                                        <p>Discussions: {{ $item->user->threads()->active()->count() }}</p>
                                        <p>Replies: {{ $item->user->replies()->active()->count() }}</p>
                                        <p>Join Date: {{ $item->user->created_at->format('Y/m/d') }}</p>
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="answer-content">
                                {!! $item->body !!}
                                @if (count($item->files))
                                    <h5 class="mt-5 font-medium">Documents uploaded by user:</h5>
                                    @foreach ($item->files as $file)
                                        <p><a href="{{ $file->path }}" target="_blank" class="link-black"><i
                                                    class="fa fa-paperclip"></i> {{ $file->name }}</a></p>
                                    @endforeach
                                @endif
                                <p class="mt-5"><b>{{ $item->user->name }} - Posted from {{ $item->location }} -
                                        {{ $item->created_at->format('Y-m-d H:i:s') }}</b></p>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <a href="#" class="btn btn-grey reply_jump" data-id="{{ $item->id }}">Reply</a>
                                    <a href="#resonse{{ $item->id }}" class="btn btn-link-2 font-medium"
                                        id="response_{{ $loop->index + 1 }}">Response #{{ $loop->index + 1 }}</a>
                                </div>

                                @foreach ($item->replies as $sub_reply)
                                    <div class="answer-content-reply d-flex align-items-start">
                                        <figure>
                                            <img src="{{ $sub_reply->user->image }}" alt="{{ $sub_reply->user->name }}"
                                                class="img-fluid">
                                            <figcaption>
                                                <p>{{ $sub_reply->user->name }}</p>
                                            </figcaption>
                                        </figure>
                                        <div class="answer-content-reply-text">
                                            @auth
                                                @if (auth()->user()->role == 'admin')
                                                    <div class="w-100 mb-3">
                                                        <div class="forum-change-links d-flex align-items-center">
                                                            <a href="{{ route('dashboard.replies.edit', $sub_reply->id) }}"><i
                                                                    class="fa fa-pencil"></i>Edit</a>
                                                            <a href="#" onclick="$(this).next().submit()"><i
                                                                    class="fa fa-comments-o"></i>Park</a>
                                                            <form action="{{ route('dashboard.replies.park', $sub_reply->id) }}"
                                                                method="POST" onsubmit="return confirm('Are You Sure?')">
                                                                @csrf
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endauth
                                            {!! $sub_reply->body !!}
                                            @if (count($sub_reply->files))
                                                <h5 class="mt-5 font-medium">Documents uploaded by user:</h5>
                                                @foreach ($sub_reply->files as $file)
                                                    <p><a href="{{ $file->path }}" target="_blank" class="link-black"><i
                                                                class="fa fa-paperclip"></i> {{ $file->name }}</a></p>
                                                @endforeach
                                            @endif
                                            <span class="font-14">{{ $sub_reply->created_at }}</span>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <hr>
                    @if (!$thread->is_closed)
                        <h3 class="title-h3 mb-4 editor_area">YOUR ANSWER</h3>
                        <form action="{{ route('discussions.reply', $thread->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="parent_id" id="sup_id">
                            <div class="form-group row">
                                <label for="editor" class="col-md-3 col-form-label text-uppercase">MESSAGE<span
                                        class="text-red ml-1">*</span></label>
                                <div class="col-md-9 col-lg-9">
                                    <textarea name="body" id="editor">{{ $reply->body ?? old('body') }}</textarea>
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
                                <div class="col-md-9 col-lg-9">
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="{{ $reply->location ?? old('location') }}" placeholder="Current location">
                                    @error('location')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category_id" class="col-md-3 col-form-label text-uppercase">TREAT THE POST
                                    AS</label>
                                <div class="col-md-9 col-lg-9">
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Choose</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if (($reply && $reply->category_id == $category->id) || old('category_id') == $category->id) selected @endif>{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
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
                                    <div class="col-md-9 col-lg-9">
                                        <select name="user_id" id="user_id" class="form-control">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" data-type="{{ $user->type }}"
                                                    @if (($reply && $reply->user_id == $user->id) || Auth::id() == $user->id) selected @endif>{{ $user->name }}
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
                            <div class="col-md-9 col-lg-9 offset-md-3 pl-lg-2 pl-0 pr-0 form-group">
                                <p class="font-16">
                                </p>
                                <button type="submit" value="preview" name="submit"
                                    class="btn my-btn btn-white text-uppercase mt-3">PREVIEW</button>
                                <button type="submit" value="submit" name="submit"
                                    class="btn my-btn text-uppercase mt-3 disabled-after-submit">Submit</button>
                            </div>
                        </form>
                        {!! NoCaptcha::renderJs() !!}
                    @endif
                @else
                    <div class="login-container bg-grey">
                        <h3 class="title-h3 editor_area">PLEASE LOGIN OR SIGN UP TO JOIN THE DISCUSSION</h3>

                        <form class="mt-4 login-form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-uppercase">EMAIL OR USERNAME<span
                                        class="text-red ml-1">*</span></label>
                                <div class="col-md-9 col-lg-7">
                                    <input type="text" name="email" class="form-control" id="email"
                                        value="{{ old('email') }}" placeholder="Email or Username">
                                    @error('email')
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
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Password">
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
                            <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group">
                                {!! NoCaptcha::display() !!}
                            </div>
                            <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group">
                                <button type="submit" class="btn my-btn text-uppercase">Submit</button>
                            </div>

                            <div class="col-md-9 col-lg-7 offset-md-3 pl-md-2 pl-0 pr-0 text-right">
                                <a href="{{ route('password.request') }}"
                                    class="link-3 mr-sm-5 mr-2 mb-3 no-wrap d-inline-block">Forgotten password</a>
                                <a href="{{ route('register') }}" class="link-3 mb-3 no-wrap d-inline-block">Create an
                                    account</a>
                            </div>
                        </form>
                        {!! NoCaptcha::renderJs() !!}
                    </div>
                @endauth
                <hr class="donation-separator">
                <div class="donation">
                    @include('layouts.parts._donation')
                </div>
            </div>

            <div class="section-right">
                <h3 class="font-medium">Posted by</h3>
                <figure class="d-flex posted-by">
                    <img src="{{ $thread->user->image }}" alt="{{ $thread->user->name }}">
                    <figcaption>
                        <h4 class="font-medium"><a href="{{ route('users.show', $thread->user_id) }}"
                                class="font-medium link-black">{{ $thread->user->name }}</a></h4>
                        <p>{{ $thread->user->job_title }}</p>
                        <p><a
                                href="{{ route('organisations.show', $thread->user->organisation_id) }}">{{ $thread->user->organisation->name }}</a>
                        </p>
                        <p>Discussions: {{ $thread->user->threads()->active()->count() }}</p>
                        <p>Replies: {{ $thread->user->replies()->active()->count() }}</p>
                    </figcaption>
                </figure>

                <h5 class="font-medium mb-4">Related Discussions</h5>

                @foreach ($related_threads as $related_thread)
                    <hr>
                    <a class="link-black"
                        href="{{ route('discussions.show', $related_thread->id) }}">{{ $related_thread->subject }}</a>
                @endforeach
                <hr>
                @include('layouts.parts._adds_right')
            </div>
        </div>
    </div>

    @include('discussions.parts._modals')

@endsection


@section('scripts')
  <--  <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>-->
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

        $('#user_id').select2();

        $("#files").on("change", function() {
            if ($("#files")[0].files.length > 4) {
                $("#files").val('');
                alert("You can select only 4 files");

            }
        });

        $(".reply_jump").click(function() {
            var supid = $(this).attr("data-id");
            $('#sup_id').val(supid);

            $('html,body').animate({
                scrollTop: $(".editor_area").offset().top
            });
        });

        $("oembed").each(function(index) {
            let id = getId($(this).attr('url'));
            let html = '<iframe width="560" height="315" src="//www.youtube.com/embed/' +
                id + '" frameborder="0" allowfullscreen></iframe>';
            $(this).parent().html(html);
        });


        function getId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);

            return (match && match[2].length === 11) ?
                match[2] :
                null;
        }

        $('[name="files[]"]').change(function() {
            if (this.files[0].size > (5 * 1024 * 1024)) {
                $(this).val('');
                alert('Sorry, your file size is too large. Please upload a maximum of 5 MB')
            }
        })
    </script>
@endsection
