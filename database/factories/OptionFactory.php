<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->word,
            'value' => $this->faker->word,
        ];
    }

    /**
     * Indicate that the option is a trial_days.
     *
     * @return \Database\Factories\OptionFactory
     */
    public function trialDays()
    {
        return $this->state(function (array $attributes) {
            return [
                'key' => 'trial_days',
                'value' => 3,
            ];
        });
    }
}
