<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    //get all comments of a article
    public function index($id){
        $article = Article::find($id);

        if(!$article){
            return response([
                'message'=> 'Article not found'
            ], 403);
        }

        return response([
            'article'=> $article->comments()->with('user:id')->get()
        ], 200);
    }

    // create a comment 
    public function store(Request $request, $id){
        $article = Article::find($id);

        if(!$article){
            return response([
                'message'=> 'Article not found'
            ], 403);
        }
        //validate fields
        $attrs = $request->validate([
            'comment'=> 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'article_id' => $id, 
            'user_id' => auth()->user()->id 
        ]);

        return response([
            'message'=> 'Comment created'
        ], 200);
    }

    //update a comment 

    public function update(Request $request, $id){
        
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message' => 'Comment not found'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied'
            ], 403);
        }

        //validate fields 
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'Comment update'
        ], 200);
    }

    //delete a comment 

    public function destroy($id){
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message' => 'Comment not found'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted'
        ], 200);
    }

    
}
