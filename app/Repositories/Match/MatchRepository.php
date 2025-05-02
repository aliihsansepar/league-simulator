<?php

namespace App\Repositories\Match;

use App\Models\Matches;
use App\Services\Fixture\FixtureService;
use App\Services\TeamStandings\StandingService;

class MatchRepository implements MatchRepositoryInterface
{
    public function __construct(
        private StandingService $standingService,
        private FixtureService $fixtureService
    ) {}

    public function getAll()
    {
        return Matches::with('fixture')->get();
    }

    public function getById(int $id)
    {
        $match = Matches::find($id);

        if (! $match) {
            throw new \Exception('Match not found');
        }

        return $match;
    }

    public function updateOrCreate(array $data)
    {
        return Matches::updateOrCreate([
            'fixture_id' => $data['fixture_id'],
            'home_id' => $data['home_id'],
            'away_id' => $data['away_id'],
        ], [
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
        ]);
    }

    public function reset(): void
    {
        Matches::truncate();
        $this->standingService->generateStandingSkeleton();
        $this->fixtureService->reset();
    }
}
