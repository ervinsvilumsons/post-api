<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\DB::table('posts')->count() === 0) {
            $categoryIds = Category::pluck('id');

            Post::factory()
                ->count(21)
                ->create()
                ->each(function ($post) use ($categoryIds) {
                    $post->categories()->attach($categoryIds->random(rand(1, 3))->toArray());

                    Comment::factory()
                        ->count(rand(1, 5))
                        ->for($post)
                        ->create();
                });
        }
    }
}
