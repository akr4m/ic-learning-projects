<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Database Seeder
 *
 * এটি main seeder যা অন্যান্য seeder-কে call করে।
 * php artisan db:seed command দিলে এটি run হয়।
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Database-এ demo data যোগ করা
     */
    public function run(): void
    {
        // Test user তৈরি
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Recipe seeder call করা
        $this->call([
            RecipeSeeder::class,
        ]);
    }
}
