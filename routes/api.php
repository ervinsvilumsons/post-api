<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.'], function () {
    Route::post('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\AuthController@login']);
    Route::post('/register', ['as' => 'register', 'uses' => 'App\Http\Controllers\AuthController@register']);
    Route::post('/logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\AuthController@logout'])->middleware('auth:sanctum');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['as' => 'categories.'], function () {
        Route::get('/categories', ['as' => 'index', 'uses' => 'App\Http\Controllers\CategoryController@index']);
    });

    Route::group(['as' => 'posts.'], function () {
        Route::get('/posts', ['as' => 'index', 'uses' => 'App\Http\Controllers\PostController@index']);
        Route::get('/posts/{post}', ['as' => 'show', 'uses' => 'App\Http\Controllers\PostController@show']);
    });

    Route::group(['as' => 'users.'], function () {
        Route::delete('/user/comment/{comment}', ['as' => 'deleteComment', 'uses' => 'App\Http\Controllers\UserController@deleteComment'])->middleware('ownership:comment');
        Route::post('/user/comment', ['as' => 'createComment', 'uses' => 'App\Http\Controllers\UserController@createComment']);

        Route::get('/user/posts', ['as' => 'getPosts', 'uses' => 'App\Http\Controllers\UserController@getPosts']);
        Route::put('/user/post/{post}', ['as' => 'updatePost', 'uses' => 'App\Http\Controllers\UserController@updatePost'])->middleware('ownership:post');
        Route::delete('/user/post/{post}', ['as' => 'deletePost', 'uses' => 'App\Http\Controllers\UserController@deletePost'])->middleware('ownership:post');
        Route::post('/user/post', ['as' => 'createPost', 'uses' => 'App\Http\Controllers\UserController@createPost']);

        Route::get('/user', ['as' => 'show', 'uses' => 'App\Http\Controllers\UserController@show']);
    });
});
