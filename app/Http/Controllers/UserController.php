<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        return new UserResource(auth()->user());
    }

    public function createComment(CommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        $comment->load('author');

        return new CommentResource($comment);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }

    public function getPosts()
    {
        return PostResource::collection(
            Post::with(['author', 'categories'])
                ->withCount('comments')
                ->filterCategories(request()->query('categories'))
                ->searchTerm(request()->query('search'))
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'DESC')
                ->paginate()
        );
    }

    public function createPost(PostRequest $request)
    {
        $post = Post::create($request->only('user_id', 'title', 'content'));
        $post->categories()->attach($request->input('categories', []));

        return new PostResource($post);
    }

    public function updatePost(Post $post, PostRequest $request)
    {
        $post->update($request->only('user_id', 'title', 'content'));
        $post->categories()->sync($request->input('categories', []));

        return new PostResource($post);
    }

    public function deletePost(Post $post)
    {
        $post->comments()->delete();
        $post->categories()->detach();
        $post->delete();

        return response()->noContent();
    }
}
