<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDevice>
 */
class UserDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => '2',
            'token_id' => null,
            'device_name' => $this->faker->randomElement(['iPhone 14', 'Chrome on Mac', 'Samsung S22', 'Firefox on Windows']),
            'device_type' => $this->faker->randomElement(['mobile', 'web', 'desktop']),
            'platform' => $this->faker->randomElement(['iOS', 'Android', 'Windows', 'macOS', 'Linux']),
            'ip_address' => $this->faker->ipv4(),
            'last_active_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
