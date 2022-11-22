<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//protected routes 
Route::group(['middleware' => ['auth:sanctum']], function(){

    //user
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Post

    Route::get('/articles', [ArticleController::class, 'index']); // all articles
    Route::post('/articles', [ArticleController::class, 'store']); // create articles
    Route::get('/articles/{id}', [ArticleController::class, 'show']); // get single articles
    Route::put('/articles/{id}', [ArticleController::class, 'update']); //update articles 
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']); //delete articles

    // Comment
    Route::get('/articles/{id}/comments', [CommentController::class, 'index']); // all comments
    Route::post('/articles/{id}/comments', [CommentController::class, 'store']); // create comment
    Route::put('/comments/{id}', [CommentController::class, 'update']); // update comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); //delete article
    
    //
    Route::post('/articles/{id}/likes', [LikeController::class, 'likes']); // like or dislike back a article  

});