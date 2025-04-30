<?php

namespace Database\Factories;

use App\Models\Season;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matches>
 */
class MatchFactory extends Factory
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
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'match_date' => fake()->dateTimeBetween('-4 weeks', 'now'),
            'home_goals' => fake()->numberBetween(0, 10),
            'away_goals' => fake()->numberBetween(0, 10),
            'is_played' => fake()->boolean(),
        ];
    }
}
