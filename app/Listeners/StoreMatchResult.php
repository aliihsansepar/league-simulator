<?php

namespace App\Listeners;

use App\Events\MatchCompleted;
use App\Services\Fixture\FixtureService;
use App\Services\Match\MatchService;

class StoreMatchResult
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private MatchService $matchService,
        private FixtureService $fixtureService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchCompleted $event): void
    {
        $this->matchService->createMatch([
            'fixture_id' => $event->fixture->id,
            'home_score' => $event->homeTeamGoals,
            'away_score' => $event->awayTeamGoals,
            'home_id' => $event->fixture->home_id,
            'away_id' => $event->fixture->away_id,
        ]);

        $this->fixtureService->updateFixture($event->fixture->id, [
            'is_played' => true,
        ]);
    }
}
