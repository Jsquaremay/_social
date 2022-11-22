<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;


class ArticleController extends Controller
{
    // get all articles
    public  function index(){
        return response([
            'articles' => Article::orderBy('created_at', 'desc')->with('user:id, name, image')->withCount('comments', 'likes')->get()
        ], 200);
    }

    // get single article 

    public function show($id){
        return response([
            'article' => Article::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }


    // create a article
    public function store(Request $request){
        //validate fields 
        $attrs = $request->validate([
            'body'=> 'required|string'
        ]);

        $article = Article::create([
            'body' =>$attrs['body'], 
            'user_id'=> auth()->user()->id
        ]);

        // for now skip for article image

        return response([
            'message' => 'Article created',
            'post' => $article
        ], 200);
    }

    // update a article
    public function update(Request $request, $id){

        $article = Article::find($id);

        if (!$article){
            return response([
                'message'=> 'Article not found'
            ], 403);
        }

        if($article->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied'
            ], 403);
        }

        //validate fields 
        $attrs = $request->validate([
            'body'=> 'required|string'
        ]);

        $article->update([
            'body'=> $attrs['body']
        ]);

        // for now skip for article image

        return response([
            'message' => 'Article update',
            'article' => $article
        ], 200);
    }

    //delete article
    public function destroy($id){
        $article = Article::find($id);

        if(!$article){
            return response([
                'message'=>'Article not found'
            ], 403);
        }

        if($article->user_id != auth()->user()->id){
            return response([
                'message'=>'Permission denied'
            ], 403);
        }
        $article->comments()->delete();
        $article->comments()->delete();
        $article->delete();

        return response([
            'message' => 'Article delete'
        ], 200);
    }
}
