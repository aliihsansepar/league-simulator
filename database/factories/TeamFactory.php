<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'name' => fake()->name(),
            'goalkeeper_strength' => fake()->numberBetween(1, 10),
            'defender_strength' => fake()->numberBetween(1, 10),
            'midfielder_strength' => fake()->numberBetween(1, 10),
            'forward_strength' => fake()->numberBetween(1, 10),
        ];
    }
}
