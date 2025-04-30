<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    public function standings()
    {
        return $this->hasMany(Standing::class);
    }
}
