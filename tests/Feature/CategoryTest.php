<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
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
    public function testCategories(): void
    {
        $this
            ->actingAsAuthorized()
            ->get('/api/categories')
            ->assertStatus(Response::HTTP_OK);
    }
}
