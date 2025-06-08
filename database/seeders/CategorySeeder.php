<?php

namespace Database\Seeders;

use App\Models\Category;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\DB::table('categories')->count() === 0) {
            $categories = [
                'Art',
                'Health', 
                'Lifestyle', 
                'Sport',
                'Technology', 
            ];

            foreach($categories as $category) {
                Category::factory(['title' => $category])->create();
            }
        }
    }
}
