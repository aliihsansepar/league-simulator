<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'goalkeeper_strength',
        'defender_strength',
        'midfielder_strength',
        'forward_strength',
    ];

    public function matches()
    {
        return $this->hasMany(Matches::class, 'home_team_id', 'id');
    }

    public function standing()
    {
        return $this->hasOne(Standing::class);
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function homeFixtures()
    {
        return $this->hasMany(Fixture::class, 'home_team_id', 'id');
    }

    public function awayFixtures()
    {
        return $this->hasMany(Fixture::class, 'away_team_id', 'id');
    }

    public function getPointsAttribute()
    {
        return $this->wins * 3 + $this->draws;
    }

    public function getGoalDifferenceAttribute()
    {
        return $this->goals_for - $this->goals_against;
    }

    public function getGoalsForAttribute()
    {
        return $this->homeFixtures()->sum('home_team_goals') + $this->awayFixtures()->sum('away_team_goals');
    }

    public function getGoalsAgainstAttribute()
    {
        return $this->homeFixtures()->sum('away_team_goals') + $this->awayFixtures()->sum('home_team_goals');
    }

    public function getWinsAttribute()
    {
        return $this->homeFixtures()->where('home_team_goals', '>', 'away_team_goals')->count() +
            $this->awayFixtures()->where('away_team_goals', '>', 'home_team_goals')->count();
    }

    public function getDrawsAttribute()
    {
        return $this->homeFixtures()->where('home_team_goals', '=', 'away_team_goals')->count() +
            $this->awayFixtures()->where('away_team_goals', '=', 'home_team_goals')->count();
    }

    public function getLossesAttribute()
    {
        return $this->homeFixtures()->where('home_team_goals', '<', 'away_team_goals')->count() +
            $this->awayFixtures()->where('away_team_goals', '<', 'home_team_goals')->count();
    }

    // Team form is the number of wins, draws, and losses in the last 5 games
    public function getTeamFormAttribute()
    {
        return $this->homeFixtures()->where('home_team_goals', '>', 'away_team_goals')->orderBy('date', 'desc')->limit(5)->count() +
            $this->awayFixtures()->where('away_team_goals', '>', 'home_team_goals')->orderBy('date', 'desc')->limit(5)->count();
    }

    public function getNextFixtureAttribute()
    {
        return $this->fixtures()->where('is_played', false)->orderBy('date', 'asc')->first();
    }
}
