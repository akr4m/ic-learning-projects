<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory - Test User তৈরি করার জন্য
 *
 * Factory Pattern ব্যবহার করে সহজে test data তৈরি করা যায়।
 * Default password সব user এর জন্য 'password'।
 *
 * Usage:
 * User::factory()->create() - Random user
 * User::factory()->editor()->create() - Editor user
 * User::factory()->author()->create() - Author user
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Default password cache করে রাখা হয় performance এর জন্য
     */
    protected static ?string $password;

    /**
     * Default state - Author role
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'author', // Default role
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Author state - Author role দিয়ে user create করে
     * Usage: User::factory()->author()->create()
     */
    public function author(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'author',
        ]);
    }

    /**
     * Editor state - Editor role দিয়ে user create করে
     * Usage: User::factory()->editor()->create()
     */
    public function editor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'editor',
        ]);
    }

    /**
     * Unverified state - Email unverified user
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
