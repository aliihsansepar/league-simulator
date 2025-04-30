<?php

namespace Database\Factories;

use App\Models\Matches;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FixtrueFactory extends Factory
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
            'week' => fake()->numberBetween(1, 38),
            'match_id' => Matches::factory(),
        ];
    }
}
