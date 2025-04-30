<?php

namespace Database\Factories;

use App\Models\Season;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StandingsFactory extends Factory
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
            'team_id' => Team::factory(),
            'position' => fake()->numberBetween(1, 20),
            'points' => fake()->numberBetween(0, 100),
            'wins' => fake()->numberBetween(0, 100),
            'draws' => fake()->numberBetween(0, 100),
            'losses' => fake()->numberBetween(0, 100),
            'goals_for' => fake()->numberBetween(0, 100),
            'goals_against' => fake()->numberBetween(0, 100),
            'goal_difference' => fake()->numberBetween(0, 100),
            'played_matches' => fake()->numberBetween(0, 100),
        ];
    }
}
