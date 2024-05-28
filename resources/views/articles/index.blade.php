@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 800px">

        {{ $articles->links() }}

        @if (session("info"))
            <div class="alert alert-info">
                {{ session("info") }}
            </div>
        @endif

        @foreach ($articles as $article)
            <div class="card mb-2">
                @if ($article->video)
                    <video style="height: 250px" controls>
                        <source src="{{ asset('storage/videos/'. $article->video)}}" type="video/mp4">
                    </video>
                @elseif ($article->photo)
                    <img src="{{ asset('storage/photos/'. $article->photo)}}" alt="Photo"
                    class="card-img-top" style="height: 250px"
                    >
                @endif
                <div class="card-body">
                    <h3 class="card-title">
                        {{ $article->title }}
                    </h3>
                    <div class="text-muted">
                        <b class="text-success">
                            {{$article->user->name}}
                        </b>
                        <b>Category: </b>
                        {{ $article->category->name}}
                        <b>Comments: </b>
                        {{ count($article->comments)}}
                        {{ $article->created_at->diffForHumans() }}
                    </div>
                    <div class="mb-2">
                        {{ $article->body }}
                    </div>
                    <a href="{{ url("/articles/detail/$article->id")}}">
                        View Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
