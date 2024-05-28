<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','detail']);
    }

    public function index()
    {
        $data = Article::latest()->paginate(5);
        return view('articles.index', [
            'articles' => $data
        ]);
    }

    public function detail($id)
    {
        $article = Article::find($id);

        return view('articles.detail',[
            'article' => $article,
        ]);
    }

    public function delete($id)
    {
        $article = Article::find($id);

        if(Gate::allows('delete-article', $article)) {
            $article->delete();
            $this->deletePhoto($article->photo, $article->video);
            return redirect("/articles")->with("info", "Deleted an article");
        }

        return back()->with('info', "Unauthorize to delete");
    }


    public function add()
    {
        $categories = Category::all();
        return view('articles.add',[ 'categories' => $categories]);
    }

    public function create()
    {
        $validator = validator(request()->all(), [
            "title" => "required",
            "photo" => "extensions:jpg,bmp,png,mp4",
            "body" => "required",
            "category_id" => "required",
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        };

        $article = New Article;
        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->user_id = auth()->id();
        if(request()->hasFile('photo'))
        {
            $photoName = $this->storeFile(request()->file('photo'));
            $extension = request()->file('photo')->getClientOriginalExtension();

            if($extension != "mp4")
            {
                $article->photo = $photoName;
            }
            else
            {
                $article->video = $photoName ;
            }
        };
        $article->save();

        return redirect('/articles');
    }

    public function edit($id)
    {
        $article = Article::find($id);

        if(Gate::allows('update-article', $article)) {
            $categories = Category::all();

            return view('articles.update',['article' => $article, 'categories' => $categories]);
        };

        return back()->with('info', "Unauthorize to update!");
    }

    public function update($id)
    {
        $validator = validator(request()->all(), [
            "title" => "required",
            "photo" => "extensions:jpg,bmp,png,mp4",
            "body" => "required",
            "category_id" => "required",
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        };

        $article = Article::find(request()->id);
        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->user_id = auth()->id();

        if(request()->hasFile('photo'))
        {
            $this->deletePhoto($article->photo, $article->video);
            $photoName = $this->storeFile(request()->file('photo'));
            $extension = request()->file('photo')->getClientOriginalExtension();

            if($extension != "mp4")
            {
                $article->photo = $photoName ;
                $article->video = null;
            }
            else
            {
                $article->video = $photoName;
                $article->photo = null;
            }
        };

        $article->save();
        return redirect("/articles/detail/$id");

    }

    public function storeFile($file)
    {
        $fileName = $file->hashName();
        $extension = $file->getClientOriginalExtension();

        if($extension != "mp4")
        {
            Storage::putFileAs('public/photos', $file, $fileName);
        }
        else
        {
            Storage::putFileAs('public/videos', $file, $fileName);
        }

        return $fileName;
    }

    public function deletePhoto($photo, $video)
    {
        if($video){
            $filePath = "public/videos/".$video;
        }elseif($photo){
            $filePath = "public/photos/".$photo;
        }

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }
        return 0;
    }
}
