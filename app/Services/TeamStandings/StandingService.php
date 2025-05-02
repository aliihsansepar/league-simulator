<?php

namespace App\Services\TeamStandings;

use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\TeamStanding\TeamStandingRepositoryInterface;
use Illuminate\Support\Collection;

class StandingService
{
    const CACHE_KEY = 'team_standings';

    const CACHE_TTL = 60 * 60 * 2; // 2 hours

    const CACHE_REFRESH_INTERVAL = 60 * 60; // 1 hours

    public function __construct(
        private TeamRepositoryInterface $teamRepository,
        private TeamStandingRepositoryInterface $teamStandingRepository,
        private FixtureRepositoryInterface $fixtureRepository
    ) {}

    public function generateStandingSkeleton()
    {
        $this->teamStandingRepository->truncate();

        $teams = $this->teamRepository->getTeams();

        foreach ($teams as $index => $team) {
            $this->teamStandingRepository->create([
                'team_id' => $team->id,
                'points' => 0,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'position' => $index + 1,
            ]);
        }
    }

    /**
     * Return the league standings table (sorted by points and head to head).
     */
    public function getTeamStandings()
    {
        return $this->teamStandingRepository->getAll();
    }

    public function getByTeamId(int $teamId)
    {
        return $this->teamStandingRepository->getByTeamId($teamId);
    }

    public function calculatePoints(int $wins, int $draws)
    {
        return $wins * 3 + $draws;
    }

    public function calculateGoalDifference(int $goalsFor, int $goalsAgainst)
    {
        return $goalsFor - $goalsAgainst;
    }

    public function wins(int $homeTeamGoals, int $awayTeamGoals): int
    {
        return $homeTeamGoals > $awayTeamGoals ? 1 : 0;
    }

    public function draws(int $homeTeamGoals, int $awayTeamGoals): int
    {
        return $homeTeamGoals == $awayTeamGoals ? 1 : 0;
    }

    public function losses(int $homeTeamGoals, int $awayTeamGoals): int
    {
        return $homeTeamGoals < $awayTeamGoals ? 1 : 0;
    }

    public function calculatePosition(int $points, int $goalDifference): Collection
    {
        $standings = $this->teamStandingRepository->getAll();

        $standings->each(function ($standing) use ($points, $goalDifference) {
            if ($standing->points > $points || ($standing->points == $points && $standing->goal_difference > $goalDifference)) {
                $standing->position++;
            } elseif ($standing->points < $points || ($standing->points == $points && $standing->goal_difference < $goalDifference)) {
                $standing->position--;
            } else {
                $standing->position = $standing->position;
            }
        });

        return $standings;
    }
}
