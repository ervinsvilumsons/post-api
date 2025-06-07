<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @property string
     */
    public string $urlPrefix = '/api/user';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testAuthorizedUser(): void
    {
        $this
            ->actingAsAuthorized()
            ->get($this->urlPrefix)
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testUnauthorizedUser(): void
    {
        $this
            ->get($this->urlPrefix)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testComment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized($user)
            ->post("{$this->urlPrefix}/comment", [
                'post_id' => $post->id,
                'message' => 'Hello world!',
            ])
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @return void
     */
    public function testInvalidComment(): void
    {
        $this
            ->actingAsAuthorized()
            ->post("{$this->urlPrefix}/comment")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testUnauthorizedComment(): void
    {
        $this
            ->post("{$this->urlPrefix}/comment")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testDeleteComment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory(['message' => 'Hello world!'])->for($user, 'author')->create();

        $this
            ->actingAsAuthorized($user)
            ->delete("{$this->urlPrefix}/comment/{$comment->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @return void
     */
    public function testDeleteForbiddenComment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized()
            ->delete("{$this->urlPrefix}/comment/{$comment->id}")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @return void
     */
    public function testDeleteCommentNotFound(): void
    {
        $this
            ->actingAsAuthorized()
            ->delete("{$this->urlPrefix}/comment/999999")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testUnauthorizedDeleteComment(): void
    {
        $comment = Comment::factory()->create();

        $this
            ->delete("{$this->urlPrefix}/comment/{$comment->id}")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testAuthorizedPosts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();
        
        $this
            ->actingAsAuthorized($user)
            ->get("{$this->urlPrefix}/posts")
            ->assertJsonFragment(['title' => $post->title])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testUnuthorizedPosts(): void
    {
        $this
            ->get("{$this->urlPrefix}/posts")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testCreatePost(): void
    {
        $this
            ->actingAsAuthorized()
            ->post("{$this->urlPrefix}/post", [
                'title' => 'Hello world!',
                'content' => 'Hello world!',
                'categories' => [1],
            ])
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @return void
     */
    public function testCreateInvalidPost(): void
    {
        $this
            ->actingAsAuthorized()
            ->post("{$this->urlPrefix}/post")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testCreateUnauthorizedPost(): void
    {
        $this
            ->post("{$this->urlPrefix}/post")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testEditPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized($user)
            ->put("{$this->urlPrefix}/post/{$post->id}", [
                'title' => 'Hello world!',
                'content' => 'Hello world!',
                'categories' => [1],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testEditInvalidPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized($user)
            ->put("{$this->urlPrefix}/post/{$post->id}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testEditForbiddenPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized()
            ->put("{$this->urlPrefix}/post/{$post->id}", [
                'title' => 'Hello world!',
                'content' => 'Hello world!',
                'categories' => [1],
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @return void
     */
    public function testEditNotFoundPost(): void
    {
        $this
            ->actingAsAuthorized()
            ->put("{$this->urlPrefix}/post/999999", [
                'title' => 'Hello world!',
                'content' => 'Hello world!',
                'categories' => [1],
            ])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testEditUnauthorizedPost(): void
    {
        $post = Post::factory()->create();

        $this
            ->put("{$this->urlPrefix}/post/{$post->id}")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testDeletePost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized($user)
            ->delete("{$this->urlPrefix}/post/{$post->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @return void
     */
    public function testDeleteForbiddenPost(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create();

        $this
            ->actingAsAuthorized()
            ->delete("{$this->urlPrefix}/post/{$post->id}")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @return void
     */
    public function testDeleteNotFoundPost(): void
    {
        $this
            ->actingAsAuthorized()
            ->delete("{$this->urlPrefix}/post/999999")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testDeleteUnauthorizedPost(): void
    {
        $post = Post::factory()->create();

        $this
            ->delete("{$this->urlPrefix}/post/{$post->id}")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
