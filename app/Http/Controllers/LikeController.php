<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Like;

class LikeController extends Controller
{
    //like or unlike
    public function likes($id){
        $article = Article::find($id);

        if(!$article){
            return response([
                'message' => 'Article not found'
            ], 403);
        }

        $like = $article->likes()->where('user_id', auth()->user()->id)->first();

        //if not liked then like

        if(!$like){
            Like::create([
                'article-id' => $id, 
                'user_id' => auth()->user()->id
            ]);
        }

        return response([
            'message' => 'Liked'
        ], 200);

        // else dislike it
        $like->delete();

        return response([
            'message' => 'Disliked'
        ], 200);
    }
}
