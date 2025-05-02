<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStanding extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
