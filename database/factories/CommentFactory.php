<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-1 month', 'now');
        $updatedAt = fake()->dateTimeBetween($createdAt, 'now');

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'message' => fake()->paragraph(),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
