<?php

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FixtureFactory extends Factory
{
    protected $model = Fixture::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_id' => Team::factory(),
            'away_id' => Team::factory(),
            'week' => fake()->numberBetween(1, 38),
            'date' => fake()->dateTimeBetween('-4 weeks', 'now'),
        ];
    }
}
