<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $fillable = [
        'season_id',
        'team_id',
        'position',
        'points',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'played_matches',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
