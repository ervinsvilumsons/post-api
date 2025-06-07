<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    /**
     * @property User|null $user
     * @return self
     */
    protected function actingAsAuthorized(?User $user = null): self
    {
        $user = $user ?? User::factory()->create();

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
        ]);
    }
}
