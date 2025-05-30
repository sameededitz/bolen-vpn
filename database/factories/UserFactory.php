<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => Str::slug($this->faker->unique()->userName()),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'registration_date' => now(),
            'last_login' => $this->faker->optional()->dateTimeThisYear(),
            'online_at' => $this->faker->optional()->dateTimeThisMonth(),
            'role' => 'user',
            'google_id' => $this->faker->optional()->uuid(),
            'apple_id' => $this->faker->optional()->uuid(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Define an admin state.
     */
    public function admin(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ];
        });
    }

    /**
     * Define a user state.
     */
    public function user(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user12345'),
                'role' => 'user',
            ];
        });
    }
}
