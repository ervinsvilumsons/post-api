<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
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

    /**
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource(
            $post->load([
                'author', 
                'categories', 
                'comments.author',
            ])
            ->loadCount('comments')
        );
    }
}
