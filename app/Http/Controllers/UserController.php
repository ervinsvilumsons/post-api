<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * @return UserResource
     */
    public function show(): UserResource
    {
        return new UserResource(auth()->user());
    }

    /**
     * @param CommentRequest $request
     * @return CommentResource
     */
    public function createComment(CommentRequest $request): CommentResource
    {
        $comment = Comment::create($request->validated());
        $comment->load('author');

        return new CommentResource($comment);
    }

    /**
     * @param Comment $comment
     * @return Response
     */
    public function deleteComment(Comment $comment): Response
    {
        $comment->delete();

        return response()->noContent();
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getPosts(): AnonymousResourceCollection
    {
        return PostResource::collection(
            auth()->user()
                ->posts()
                ->with(['author', 'categories'])
                ->withCount('comments')
                ->filterCategories(request()->query('categories'))
                ->searchTerm(request()->query('search'))
                ->orderBy('created_at', 'DESC')
                ->paginate()
        );
    }

    /**
     * @param PostRequest $request
     * @return PostResource
     */
    public function createPost(PostRequest $request): PostResource
    {
        $post = Post::create($request->only('user_id', 'title', 'content'));
        $post->categories()->attach($request->input('categories', []));

        return new PostResource($post);
    }

    /**
     * @param Post $post
     * @param PostRequest $request
     * @return PostResource
     */
    public function updatePost(Post $post, PostRequest $request): PostResource
    {
        $post->update($request->only('user_id', 'title', 'content'));
        $post->categories()->sync($request->input('categories', []));

        return new PostResource($post);
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function deletePost(Post $post): Response
    {
        $post->comments()->delete();
        $post->categories()->detach();
        $post->delete();

        return response()->noContent();
    }
}
