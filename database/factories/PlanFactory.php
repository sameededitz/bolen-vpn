<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function trial(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'New User 3 Day Trial (Do Not Delete)',
                'slug' => 'free-trial',
                'description' => 'This is a trial plan for new users. It expires in 3 days.',
                'price' => 0,
                'duration' => 3,
                'duration_unit' => 'day',
            ];
        });
    }
}
