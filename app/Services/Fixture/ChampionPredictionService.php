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
        $maxPossiblePoints = [];
        $goalDifferences = [];
        $totalExpectedPoints = 0;
        $remainingMatchesCount = count($remainingMatches);

        // Calculate current and maximum possible points for each team
        foreach ($teams as $team) {
            $homePoints = $this->calculateExpectedPoints($team, $remainingMatches, $teams, true);
            $awayPoints = $this->calculateExpectedPoints($team, $remainingMatches, $teams, false);

            // Calculate remaining matches for this team
            $teamRemainingMatches = 0;
            foreach ($remainingMatches as $match) {
                if ($match['home_id'] === $team['id'] || $match['away_id'] === $team['id']) {
                    $teamRemainingMatches++;
                }
            }

            // Calculate current and max possible points
            $currentPoints = $team['standing']['points'];
            $finalPoints[$team['name']] = $currentPoints + $homePoints + $awayPoints;
            $maxPossiblePoints[$team['name']] = $currentPoints + ($teamRemainingMatches * 3);
            $goalDifferences[$team['name']] = $team['standing']['goal_difference'];

            $totalExpectedPoints += $finalPoints[$team['name']];
        }

        // Check if any team has mathematically secured the championship
        $sortedTeams = $teams;
        usort($sortedTeams, function ($a, $b) {
            // First sort by points
            if ($b['standing']['points'] !== $a['standing']['points']) {
                return $b['standing']['points'] - $a['standing']['points'];
            }

            // If points are equal, sort by goal difference
            return $b['standing']['goal_difference'] - $a['standing']['goal_difference'];
        });

        $leader = $sortedTeams[0];
        $leaderPoints = $leader['standing']['points'];
        $secondPlace = $sortedTeams[1] ?? null;

        if ($secondPlace) {
            $secondPlaceMaxPoints = $maxPossiblePoints[$secondPlace['name']];

            // If leader already has more points than second place can achieve
            if ($leaderPoints > $secondPlaceMaxPoints) {
                // Leader is already the champion with 100% probability
                $probabilities = [];
                foreach ($teams as $team) {
                    $probabilities[$team['name']] = ($team['id'] === $leader['id']) ? 100.00 : 0.00;
                }

                return $probabilities;
            }
        }

        // If no team has secured championship yet, continue with normal calculation

        // Group teams by expected final points
        $pointGroups = [];
        foreach ($finalPoints as $teamName => $points) {
            if (! isset($pointGroups[$points])) {
                $pointGroups[$points] = [];
            }
            $pointGroups[$points][] = $teamName;
        }

        // Adjust probabilities based on goal difference for teams with equal points
        $adjustedPoints = [];
        foreach ($pointGroups as $points => $teamNames) {
            if (count($teamNames) > 1) {
                // Multiple teams with the same points, adjust based on goal difference
                $totalGD = 0;
                $teamGD = [];

                foreach ($teamNames as $teamName) {
                    $gd = $goalDifferences[$teamName];
                    $teamGD[$teamName] = max(1, $gd + 10); // Ensure positive values by adding offset
                    $totalGD += $teamGD[$teamName];
                }

                foreach ($teamNames as $teamName) {
                    // Adjust points slightly based on goal difference proportion
                    $gdFactor = $teamGD[$teamName] / $totalGD;
                    $adjustedPoints[$teamName] = $points + ($gdFactor * 0.1); // Small adjustment
                }
            } else {
                // Single team with these points
                $adjustedPoints[$teamNames[0]] = $points;
            }
        }

        // Sort adjusted points
        arsort($adjustedPoints, SORT_NUMERIC);

        // Recalculate total points with adjusted values
        $totalAdjustedPoints = array_sum($adjustedPoints);

        // Distribute champion probabilities proportionally
        $probabilities = [];
        foreach ($adjustedPoints as $teamName => $points) {
            $probabilities[$teamName] = round(($points / $totalAdjustedPoints) * 100, 2);
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
