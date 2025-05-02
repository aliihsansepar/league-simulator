<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $table = 'fixtures';

    protected $fillable = [
        'home_id',
        'away_id',
        'date',
        'week',
        'is_played',
    ];

    public function homeTeam()
    {
        return $this->hasOne(Team::class, 'id', 'home_id');
    }

    public function awayTeam()
    {
        return $this->hasOne(Team::class, 'id', 'away_id');
    }

    public function match()
    {
        return $this->hasOne(Matches::class, 'fixture_id', 'id');
    }

    public function isNotPlayed()
    {
        return $this->where('is_played', false);
    }

    public function isPlayed()
    {
        return $this->where('is_played', true);
    }
}
