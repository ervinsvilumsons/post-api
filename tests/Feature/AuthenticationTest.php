<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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
    public function testValidAuthentication(): void
    {
        User::factory([
                'email' => 'test@example.com',
                'password' => 'password',
                'name' => 'Tester',
            ])
            ->create();

        $this
            ->post('/api/login', [
                'email' => 'test@example.com', 
                'password' => 'password',
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testInvalidAuthentication(): void
    {
        $this
            ->post('/api/login', [
                'email' => 'test@example.com', 
                'password' => 'password',
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testValidRegistration(): void
    {
        $this
            ->post('/api/register', [
                'email' => 'test@example.com', 
                'password' => 'password',
                'password_confirmation' => 'password',
                'name' => 'Tester',
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testInvalidRegistration(): void
    {
        $this
            ->post('/api/register', [
                'email' => 'test@example.com', 
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function testLogout(): void
    {        
        $this
            ->actingAsAuthorized()
            ->post('/api/logout')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
