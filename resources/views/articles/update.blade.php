@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px">
    @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $err)
                {{ $err }}
            @endforeach
        </div>
    @endif
    <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- @if ($article->photo)
                <div class="col">
                    <img src="{{ asset('storage/photos/'. $article->photo)}}" alt="Photo"
                    class="img-thumbnail" style="height: 250px"
                    >
                </div>
            @endif --}}
            @if ($article->video)
                <video style="height: 250px" controls>
                    <source src="{{ asset('storage/videos/'. $article->video)}}" type="video/mp4">
                </video>
            @elseif($article->photo)
                <img src="{{ asset('storage/photos/'. $article->photo)}}" alt="Photo"
                    class="card-img-top" style="height: 250px"
                >
            @endif
            <div class="col">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control mb-2" id="photo">
            </div>
        </div>
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" placeholder="Title"
            class="form-control mb-2" value="{{$article->title}}">

        <label for="title" class="form-label">Body</label>
        <textarea name="body" placeholder="Body"
            class="form-control mb-2">{{$article->body}}</textarea>

        <label for="title" class="form-label">Category</label>
        <select name="category_id" class="form-select mb-2">
            @foreach($categories as $category)
                <option value="{{$category->id}}"
                    @selected($article->category_id == $category->id)>
                    {{$category->name}}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary">Update Article</button>
    </form>
</div>
@endsection
