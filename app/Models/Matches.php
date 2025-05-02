<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'home_id',
        'away_id',
        'home_score',
        'away_score',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'home_id', 'away_id', 'id');
    }

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function getIsPlayedAttribute()
    {
        return $this->fixture->is_played;
    }
}
