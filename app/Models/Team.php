<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function standing(): HasOne
    {
        return $this->hasOne(TeamStanding::class, 'team_id', 'id');
    }

    public function homeFixtures()
    {
        // it can be home or away
        return $this->hasMany(Fixture::class, 'home_id', 'id');
    }

    public function awayFixtures()
    {
        return $this->hasMany(Fixture::class, 'away_id', 'id');
    }

    public function matches()
    {
        return $this->hasMany(Matches::class, 'home_id', 'id');
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function getPositionAttribute(): int
    {
        return $this->standing->position;
    }

    public function getPointsAttribute(): int
    {
        return $this->standing->points;
    }

    public function getWinsAttribute(): int
    {
        return $this->standing->wins;
    }

    public function getDrawsAttribute(): int
    {
        return $this->standing->draws;
    }

    public function getLossesAttribute(): int
    {
        return $this->standing->losses;
    }

    public function getGoalsForAttribute(): int
    {
        return $this->standing->goals_for;
    }

    public function getGoalsAgainstAttribute(): int
    {
        return $this->standing->goals_against;
    }

    public function getGoalDifferenceAttribute(): int
    {
        return $this->standing->goal_difference;
    }

    public function getFormAttribute()
    {
        return $this->fixtures()->where('is_played', true)->orderBy('week', 'desc')->take(5)->get();
    }

    public function getNextFixtureAttribute()
    {
        return $this->fixtures()->orderBy('week', 'asc')->first();
    }

    public function getRemainingFixturesAttribute()
    {
        return $this->fixtures()->where('is_played', false)->orderBy('week', 'asc')->get();
    }
}
