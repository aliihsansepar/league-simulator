<?php

namespace App\Services\Fixture;

use App\Events\FixtureGenerated;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Services\Team\TeamService;

class FixtureService
{
    public function __construct(
        private FixtureRepositoryInterface $fixtureRepository,
        private TeamService $teamService
    ) {}

    public function getAllFixtures()
    {
        return $this->fixtureRepository->getAllFixtures();
    }

    public function generateFixtures()
    {
        $teams = $this->teamService->getTeams();

        $fixtures = [];
        $firstLegFixtures = [];
        $secondLegFixtures = [];
        $teamCount = count($teams);
        $halfTeamCount = $teamCount / 2;
        $totalWeeks = $this->getTotalWeeks($teamCount);
        $isOdd = $this->isWeekCountOdd($teamCount);

        if ($isOdd) {
            $teams[] = new Team([
                'name' => 'Dummy Team',
                'id' => 0,
            ]);
            $teamCount++;
        }

        $teams = $teams->shuffle();
        // Generate fixtures
        for ($i = 0; $i < $totalWeeks; $i++) {
            for ($j = 0; $j < $halfTeamCount; $j++) {

                $home = $teams[$j];
                $away = $teams[$teamCount - $j - 1];

                if ($home->id === $away->id || $home->id === 0 || $away->id === 0) {
                    continue;
                }

                if ($i % 2 === 0) {
                    $firstLegFixtures[] = [
                        'home_id' => $home->id,
                        'away_id' => $away->id,
                        'week' => $i + 1,
                        'date' => now()->addDays($i * 7)->format('Y-m-d'),
                        'is_played' => false,
                    ];
                } else {
                    $secondLegFixtures[] = [
                        'home_id' => $away->id,
                        'away_id' => $home->id,
                        'week' => $i + 1,
                        'date' => now()->addDays($i * 7)->format('Y-m-d'),
                        'is_played' => false,
                    ];
                }
            }

            // Rotate teams using round-robin algorithm
            if ($teamCount > 1) { // Ensure there are teams to rotate
                $fixedTeam = $teams->shift(); // Remove the first team temporarily
                $lastTeam = $teams->pop();    // Remove the last team
                $teams->prepend($lastTeam);  // Add the last team to the beginning (after the fixed team's original position)
                $teams->prepend($fixedTeam); // Add the fixed team back to the start
            }
        }

        // Create fixtures
        $fixtures = array_merge($firstLegFixtures, $secondLegFixtures);

        try {
            $this->fixtureRepository->createFixtures($fixtures);

            event(new FixtureGenerated($fixtures));

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create fixtures');
        }
    }

    public function getFixtureByWeek(int $week)
    {
        return $this->fixtureRepository->getFixtureByWeek($week);
    }

    public function getFixturesGroupedByWeek()
    {
        $fixtures = $this->fixtureRepository->getFixtures();

        return $fixtures->groupBy('week');
    }

    private function isWeekCountOdd(int $teamCount): bool
    {
        return $teamCount % 2 !== 0;
    }

    private function getTotalWeeks(int $teamCount): int
    {
        $isOdd = $this->isWeekCountOdd($teamCount);

        $halfWeeks = $isOdd
        ? $teamCount
        : $teamCount - 1;

        return $halfWeeks * 2;
    }

    public function updateFixture(int $id, array $data)
    {
        return $this->fixtureRepository->updateFixture($id, $data);
    }

    public function reset()
    {
        $this->fixtureRepository->destroyAll();
        $this->generateFixtures();
    }
}
