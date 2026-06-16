@extends('layouts.app')
@section('content')

    <div class="my-container bg-white pt-3 mt-4 mb-5">
        <div class="breadcrumbs_header mb-5">
            <a href="{{route('discussions.index')}}">Discussion Forum</a> &gt;
            <a href="{{route('groups.discussions', $thread->group_id)}}">{{$thread->group->name}}</a> &gt;
            {{$thread->subject}}
        </div>

        <div class="main-section d-flex flex-wrap pb-4 forum-section">

            <div class="section-left">
                <h2 class="title-h1 font-medium mb-4">{{$thread->subject}}</h2>

                {!! $thread->body !!}
                @if(count($thread->files))
                    <h5 class="mt-5 font-medium">Documents uploaded by user:</h5>
                    @foreach($thread->files as $file)
                        <p><a href="{{$file->path}}" target="_blank" class="link-black"><i class="fa fa-paperclip"></i> {{$file->name}}</a></p>
                    @endforeach
                @endif

                <h6 class="title-h6 font-medium mt-5 pt-5 mb-0">{{$thread->replies()->count()}} Answer(s)</h6>
                @if($isEdit)
                    <form action="{{route('discussions.preview.update', $thread->id)}}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" value="submit" name="submit" class="btn my-btn text-uppercase ">Update</button>
                        <a href="#" class="btn my-btn btn-white text-uppercase j-back">BACK</a>
                    </form>
                @else
                    <form action="{{route('discussions.preview.save', $thread->id)}}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" value="submit" name="submit" class="btn my-btn text-uppercase ">SAVE</button>
                        <a href="#" class="btn my-btn btn-white text-uppercase j-back">BACK</a>
                    </form>
                @endif

            </div>

            <div class="section-right">
                <h3 class="font-medium">Posted by</h3>
                <figure class="d-flex posted-by">
                    <img src="{{$thread->user->image}}" alt="{{$thread->user->name}}">
                    <figcaption>
                        <h4 class="font-medium"><a href="{{route('users.show', $thread->user_id)}}" class="font-medium link-black">{{$thread->user->name}}</a></h4>
                        <p>{{$thread->user->job_title}}</p>
                        <p><a href="{{route('organisations.show', $thread->user->organisation_id)}}">{{$thread->user->organisation->name}}</a></p>
                        <p>Total Post {{$thread->user->threads()->count()}}</p>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endsection
