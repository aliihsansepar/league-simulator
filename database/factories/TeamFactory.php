<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $shortName = strtoupper(substr($name, 0, 3));

        return [
            'name' => $name,
            'short_name' => $shortName,
            'goalkeeper_strength' => fake()->numberBetween(1, 10),
            'defense_strength' => fake()->numberBetween(1, 10),
            'midfield_strength' => fake()->numberBetween(1, 10),
            'attack_strength' => fake()->numberBetween(1, 10),
        ];
    }

    public function name(string $name): self
    {
        return $this->state([
            'name' => $name,
        ]);
    }

    public function shortName(string $shortName): self
    {
        return $this->state([
            'short_name' => $shortName,
        ]);
    }

    public function goalkeeperStrength(int $strength): self
    {
        return $this->state([
            'goalkeeper_strength' => $strength,
        ]);
    }

    public function defenseStrength(int $strength): self
    {
        return $this->state([
            'defense_strength' => $strength,
        ]);
    }

    public function midfieldStrength(int $strength): self
    {
        return $this->state([
            'midfield_strength' => $strength,
        ]);
    }

    public function attackStrength(int $strength): self
    {
        return $this->state([
            'attack_strength' => $strength,
        ]);
    }
}
