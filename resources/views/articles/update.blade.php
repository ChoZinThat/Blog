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
    <form method="post" >
        @csrf
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
