<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Liverpool',
                'short_name' => 'LIV',
                'goalkeeper_strength' => rand(1, 10),
                'defense_strength' => rand(1, 10),
                'midfield_strength' => rand(1, 10),
                'attack_strength' => rand(1, 10),
            ],
            [
                'name' => 'Arsenal',
                'short_name' => 'ARS',
                'goalkeeper_strength' => rand(1, 10),
                'defense_strength' => rand(1, 10),
                'midfield_strength' => rand(1, 10),
                'attack_strength' => rand(1, 10),
            ],
            [
                'name' => 'Aston Villa',
                'short_name' => 'AVL',
                'goalkeeper_strength' => rand(1, 10),
                'defense_strength' => rand(1, 10),
                'midfield_strength' => rand(1, 10),
                'attack_strength' => rand(1, 10),
            ],
            [
                'name' => 'Manchester City',
                'short_name' => 'MCI',
                'goalkeeper_strength' => rand(1, 10),
                'defense_strength' => rand(1, 10),
                'midfield_strength' => rand(1, 10),
                'attack_strength' => rand(1, 10),
            ],

        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
