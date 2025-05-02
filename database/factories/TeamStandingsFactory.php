<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamStanding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeamStandingFactory extends Factory
{
    protected $model = TeamStanding::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'position' => fake()->numberBetween(1, 20),
            'points' => fake()->numberBetween(0, 100),
            'wins' => fake()->numberBetween(0, 100),
            'draws' => fake()->numberBetween(0, 100),
            'losses' => fake()->numberBetween(0, 100),
            'goals_for' => fake()->numberBetween(0, 100),
            'goals_against' => fake()->numberBetween(0, 100),
            'goal_difference' => fake()->numberBetween(0, 100),
            'matches_played' => fake()->numberBetween(0, 100),
        ];
    }
}
