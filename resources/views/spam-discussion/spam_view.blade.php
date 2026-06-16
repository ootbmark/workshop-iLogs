@extends('layouts.app')

@section('add-css')
    <style>
        .donation-separator {
            margin-top: 50px;
            margin-bottom: 50px;
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
                {{--   <h6 class="title-h6 font-medium mt-3 pt-5 mb-0">{{ $thread->replies()->active()->count() }} Answer(s)</h6> --}}

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
                        {{--   <p>Discussions: {{ $thread->user->threads()->active()->count() }}</p> --}}
                        {{--     <p>Replies: {{ $thread->user->replies()->active()->count() }}</p> --}}
                    </figcaption>
                </figure>
                {{-- INVALID SPAM --}}
                <form action="{{ route('discussion-spam.invalid-spam') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $thread->id }}">
                    <button type="submit" class="btn btn-primary w-100 fw-bolder">MARK AS INVALID SPAM</button>
                </form>
                {{-- BLOCK USER --}}

            </div>
        </div>
    </div>

    @include('discussions.parts._modals')

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
