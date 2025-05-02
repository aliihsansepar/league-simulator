<?php

namespace App\Services\Fixture;

use App\Models\Team;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;

class ChampionPredictionService
{
    public function __construct(
        private FixtureRepositoryInterface $fixtureRepository,
        private TeamRepositoryInterface $teamRepository
    ) {}

    public function calculate()
    {
        $fixtures = $this->fixtureRepository->getNextWeekFixture();
        $teams = $this->teamRepository->getTeams();

        $predictions = $this->calculateChampionProbabilities($teams->toArray(), $fixtures->toArray());

        return collect($predictions)->map(function ($probability, $team) {
            return [
                'team_name' => $team,
                'percentage' => $probability,
            ];
        })->toArray();
    }

    // Calculate champion probabilities
    private function calculateChampionProbabilities(array $teams, array $remainingMatches): array
    {
        $finalPoints = [];
        $totalExpectedPoints = 0;

        // Calculate expected final points for each team
        foreach ($teams as $team) {
            $homePoints = $this->calculateExpectedPoints($team, $remainingMatches, $teams, true);
            $awayPoints = $this->calculateExpectedPoints($team, $remainingMatches, $teams, false);
            $finalPoints[$team['name']] = $team['standing']['points'] + $homePoints + $awayPoints;
            $totalExpectedPoints += $finalPoints[$team['name']];
        }

        // sort the final points
        arsort($finalPoints, SORT_NUMERIC);

        // Distribute champion probabilities proportionally
        $probabilities = [];
        foreach ($finalPoints as $teamName => $points) {
            $probabilities[$teamName] = round(($points / $totalExpectedPoints) * 100, 2);
        }

        return $probabilities;
    }

    // Calculate expected points
    private function calculateExpectedPoints(array $team, array $matches, array $teams, bool $isHome = true): float
    {
        $expectedPoints = 0;

        foreach ($matches as $match) {
            $homeTeam = collect($teams)->where('id', $match['home_id'])->first();
            $awayTeam = collect($teams)->where('id', $match['away_id'])->first();

            if ($isHome && $match['home_id'] !== $team['id']) {
                continue;
            }
            if (! $isHome && $match['away_id'] !== $team['id']) {
                continue;
            }

            $probs = $this->calculateMatchProbabilities($homeTeam, $awayTeam);
            $teamProbs = $isHome ? $probs['homeWin'] : $probs['awayWin'];

            // Expected points = (win probability × 3) + (draw probability × 1)
            $expectedPoints += ($teamProbs * 3) + ($probs['draw'] * 1);
        }

        return $expectedPoints;
    }

    // Calculate Team weighted average
    public function calculateTeamStrength(array $team): float
    {
        return (
            $team['goalkeeper_strength'] +
            $team['defense_strength'] +
            $team['midfield_strength'] +
            $team['attack_strength']
        ) / 4;
    }

    // Calculate match probabilities
    public function calculateMatchProbabilities(array $homeTeam, array $awayTeam): array
    {
        $homeStrength = $this->calculateTeamStrength($homeTeam);
        $awayStrength = $this->calculateTeamStrength($awayTeam);

        // Home team advantage
        $homeStrength *= 1.1;

        $totalStrength = $homeStrength + $awayStrength;

        $homeWinProb = $homeStrength / $totalStrength;
        $awayWinProb = $awayStrength / $totalStrength;
        $drawProb = 0.2; // Fixed draw probability
        $homeWinProb *= (1 - $drawProb);
        $awayWinProb *= (1 - $drawProb);

        return [
            'homeWin' => round($homeWinProb, 2),
            'draw' => round($drawProb, 2),
            'awayWin' => round($awayWinProb, 2),
        ];
    }
}
