<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\DB::table('users')->count() === 0) {
            User::factory([
                'name' => 'E2E User',
                'email' => 'e2e@test.com',
                'password' => 'password',
            ])
            ->create();
        }
    }
}
