<?php

namespace App\Repositories\Fixture;

use Illuminate\Database\Eloquent\Collection;

interface FixtureRepositoryInterface
{
    public function createFixtures(array $data);

    public function getAllFixtures(): Collection;

    public function getFixtures();

    public function getFixtureByWeek(int $week);

    public function getFixturesBetweenTeams(int $homeTeamId, int $awayTeamId);

    public function getFixtureBetweenTeams(int $homeTeamId, int $awayTeamId);

    public function getNextWeekFixture(?int $week = null);

    public function destroyAll(): void;

    public function updateFixture(int $id, array $data);
}
