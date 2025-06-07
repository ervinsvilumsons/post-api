<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;

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
    public function testAuthorizedPostsFirstPage(): void
    {
        $this
            ->actingAsAuthorized()
            ->get('/api/posts')
            ->assertJsonFragment(['current_page' => 1])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testAuthorizedPostsSecondPage(): void
    {
        $this
            ->actingAsAuthorized()
            ->get('/api/posts?page=2')
            ->assertJsonFragment(['current_page' => 2])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testAuthorizedPostsFilter(): void
    {
        $response = $this
            ->actingAsAuthorized()
            ->get('/api/posts?categories=1');
        $response
            ->assertStatus(Response::HTTP_OK);

        $this
            ->assertContains('Art', $response->json('data.0.categories'));
    }

    /**
     * @return void
     */
    public function testAuthorizedPostsSearch(): void
    {
        $post = Post::factory(['title' => 'Test'])->create();
        
        $this
            ->actingAsAuthorized()
            ->get('/api/posts?search=test')
            ->assertJsonFragment(['title' => $post->title])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testUnauthorizedPosts(): void
    {
        $this
            ->get('/api/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testAuthorizedPost(): void
    {
        $post = Post::factory()->create();

        $this
            ->actingAsAuthorized()
            ->get("/api/posts/{$post->id}")
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testPostNotFound(): void
    {
        $this
            ->actingAsAuthorized()
            ->get("/api/posts/999999")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testUnauthorizedPost(): void
    {
        $post = Post::factory()->create();

        $this
            ->get("/api/posts/{$post->id}")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
