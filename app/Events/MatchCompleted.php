<?php

namespace App\Events;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Fixture $fixture,
        public Team $homeTeam,
        public Team $awayTeam,
        public int $homeTeamGoals,
        public int $awayTeamGoals
    ) {
        //
    }
}
