<?php

namespace App\Listeners;

use App\Events\FixtureGenerated;
use App\Services\TeamStandings\StandingService;

class GenerateTeamStandingSkeleton
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private StandingService $standingService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(FixtureGenerated $event): void
    {
        $this->standingService->generateStandingSkeleton();
    }
}
