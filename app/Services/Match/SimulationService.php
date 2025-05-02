<?php

namespace App\Services\Match;

use App\Events\MatchCompleted;
use App\Models\Fixture;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Services\TeamStandings\StandingService;

class SimulationService
{
    public function __construct(
        private FixtureRepositoryInterface $fixtureRepository,
        private TeamRepositoryInterface $teamRepository,
        private StandingService $standingService
    ) {}

    public function simulate(array $teams): void
    {
        $homeTeam = $this->teamRepository->getTeamById($teams['homeTeamId']);
        $awayTeam = $this->teamRepository->getTeamById($teams['awayTeamId']);
        $fixture = $this->fixtureRepository->getFixtureBetweenTeams($teams['homeTeamId'], $teams['awayTeamId']);

        $homeTeamGoals = 0;
        $awayTeamGoals = 0;
        $homeTeamShotCount = 0;
        $awayTeamShotCount = 0;
        $homeTeamTackleCount = 0;
        $awayTeamTackleCount = 0;

        // Home team attacks
        $homeTeamAttacks = $this->calculateAttackOpportunities($homeTeam);
        for ($i = 0; $i < $homeTeamAttacks; $i++) {
            // Check if away team tackles the attack
            if (rand(0, 100) / 100 < $this->calculateTackleProbability($awayTeam)) {
                $awayTeamTackleCount++;

                continue; // Attack is tackled, no shot
            }

            if (rand(0, 100) / 100 < $this->calculateShotProbability($homeTeam)) {
                $homeTeamShotCount++;
                if (rand(0, 100) / 100 < $this->calculateGoalProbabilityOnShot($homeTeam, $awayTeam)) {
                    $homeTeamGoals++;
                }
            }
        }

        // Away team attacks
        $awayTeamAttacks = $this->calculateAttackOpportunities($awayTeam);
        for ($i = 0; $i < $awayTeamAttacks; $i++) {
            // Check if home team tackles the attack
            if (rand(0, 100) / 100 < $this->calculateTackleProbability($homeTeam)) {
                $homeTeamTackleCount++;

                continue; // Attack is tackled, no shot
            }

            if (rand(0, 100) / 100 < $this->calculateShotProbability($awayTeam)) {
                $awayTeamShotCount++;
                if (rand(0, 100) / 100 < $this->calculateGoalProbabilityOnShot($awayTeam, $homeTeam)) {
                    $awayTeamGoals++;
                }
            }
        }

        event(new MatchCompleted($fixture, $homeTeam, $awayTeam, $homeTeamGoals, $awayTeamGoals));
    }

    public function simulateWeek(?int $week = null): void
    {
        $fixtures = $this->fixtureRepository->getNextWeekFixture($week);

        foreach ($fixtures as $fixture) {
            $this->simulateFixture($fixture);
        }
    }

    public function simulateSeason(): void
    {
        $fixtures = $this->fixtureRepository->getFixtures();

        foreach ($fixtures as $fixture) {
            $this->simulateFixture($fixture);
        }
    }

    public function simulateFixture(Fixture $fixture): void
    {
        $homeTeam = $fixture->homeTeam;
        $awayTeam = $fixture->awayTeam;

        $this->simulate(['homeTeamId' => $homeTeam->id, 'awayTeamId' => $awayTeam->id]);
    }

    private function calculateAttackOpportunities($team): int
    {
        $teamAttackStrength = $team->attack_strength * 1.1;
        // Attack count: 5-15 range, based on team's midfield and attack strength
        $baseAttacks = rand(3, 8);
        $strengthFactor = ($team->midfield_strength + $teamAttackStrength) / 20; // 0-1 range
        $attacks = $baseAttacks + ($strengthFactor * 10); // 5-15 range

        return round($attacks);
    }

    private function calculateShotProbability($team): float
    {
        // Shot probability: 30%-70% range, based on attack strength
        return 0.3 + ($team->attack_strength / 10) * 0.4;
    }

    private function calculateGoalProbabilityOnShot($team, $opponent): float
    {
        $teamAttackStrength = $team->attack_strength * 1.1;
        // Goal probability: Attack strength / (Attack strength + Opponent defense strength + Opponent goalkeeper strength)
        $defenseStrength = $opponent->defense_strength + ($opponent->goalkeeper_strength ?? 5); // Default 5

        return $teamAttackStrength / ($teamAttackStrength + $defenseStrength);
    }

    private function calculateTackleProbability($opponent): float
    {
        // Tackle probability: Based on opponent's defense strength
        return $opponent->defense_strength / 15;
    }
}
