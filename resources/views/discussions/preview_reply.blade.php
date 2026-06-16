@extends('layouts.app')
@section('content')

    <div class="my-container bg-white pt-3 mt-4 mb-5">

        <div class="main-section d-flex flex-wrap pb-4 forum-section">
            <div class="section-left">
            <h3 class="title-h3 mb-4">YOUR ANSWER</h3>

                    <div class="d-flex flex-wrap align-items-start border-bottom pb-4 mt-2">
                        <div class="answer-user">
                            <figure>
                                <img src="{{$reply->user->image}}" alt="{{$reply->user->name}}" class="img-fluid">
                                <figcaption>
                                    <h4 class="font-medium"><a href="{{route('users.show', $reply->user_id)}}"></a>{{$reply->user->name}}</h4>
                                    <p>{{$reply->user->job_title}}</p>
                                    <p><a href="{{route('organisations.show', $reply->user->organisation_id)}}">{{$reply->user->organisation->name}}</a></p>
                                    <p>Total Posts: {{$reply->user->threads()->count()}}</p>
                                    <p>Join Date: {{$reply->user->created_at->format('Y/m/d')}}</p>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="answer-content">
                            {!! $reply->body !!}
                            @if(count($reply->files))
                                <h5 class="mt-5 font-medium">Documents uploaded by user:</h5>
                                @foreach($reply->files as $file)
                                    <p><a href="{{$file->path}}" target="_blank" class="link-black"><i class="fa fa-paperclip"></i> {{$file->name}}</a></p>
                                @endforeach
                            @endif
                            <p class="mt-5"><b>{{$reply->user->name}} - Posted from {{$reply->location}} - {{$reply->created_at->format('Y-m-d H:i:s')}}</b></p>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <p></p>
                                <a href="#" class="btn btn-link-2 font-medium" id="response_1">Response 1</a>
                            </div>
                        </div>
                    </div>

                @if($isEdit)
                    <form action="{{route('reply.preview.update', $reply->id)}}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" value="submit" name="submit" class="btn my-btn text-uppercase ">Update</button>
                        <a href="#" class="btn my-btn btn-white text-uppercase j-back">BACK</a>
                    </form>
                @else
                    <form action="{{route('reply.preview.save', $reply->id)}}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" value="submit" name="submit" class="btn my-btn text-uppercase ">SAVE</button>
                        <a href="#" class="btn my-btn btn-white text-uppercase j-back">BACK</a>
                    </form>
                @endif

            </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endsection
