<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(
            Post::with(['author', 'categories'])
                ->withCount('comments')
                ->filterCategories(request()->query('categories'))
                ->searchTerm(request()->query('search'))
                ->orderBy('created_at', 'DESC')
                ->paginate()
        );
    }

    public function show(Post $post)
    {
        return new PostResource(
            $post->load([
                'author', 
                'categories', 
                'comments.author'
            ])
            ->loadCount('comments')
        );
    }
}
