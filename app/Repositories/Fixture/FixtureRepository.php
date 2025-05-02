<?php

namespace App\Repositories\Fixture;

use App\Models\Fixture;
use App\Models\Matches;
use App\Models\TeamStanding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FixtureRepository implements FixtureRepositoryInterface
{
    public function createFixtures(array $data): void
    {
        try {
            DB::beginTransaction();

            Fixture::insert($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create fixtures');
        }
    }

    public function getAllFixtures(): Collection
    {
        try {
            $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->get();

            if ($fixtures->isEmpty()) {
                throw new \Exception('Fixtures not found');
            }

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get all fixtures');
        }
    }

    public function getFixtures(): Collection
    {
        try {
            $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->orderBy('week')->get();

            if ($fixtures->isEmpty()) {
                throw new \Exception('Fixtures not found');
            }

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fixtures');
        }

    }

    public function getFixtureByWeek(int $week): Collection
    {
        try {
            $fixtures = Fixture::with(['homeTeam', 'awayTeam'])->where('week', $week)->get();

            if (empty($fixtures)) {
                throw new \Exception('Fixtures not found');
            }

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fixtures');
        }
    }

    public function getFixturesBetweenTeams(int $homeTeamId, int $awayTeamId): Collection
    {
        try {
            $fixtures = Fixture::query()
                ->where(function ($query) use ($homeTeamId, $awayTeamId) {
                    $query->where('home_id', $homeTeamId)->where('away_id', $awayTeamId);
                })
                ->orWhere(function ($query) use ($homeTeamId, $awayTeamId) {
                    $query->where('home_id', $awayTeamId)->where('away_id', $homeTeamId);
                })
                ->get();

            if (empty($fixtures)) {
                throw new \Exception('Fixture not found');
            }

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get fixture by team: '.$e->getMessage());
        }
    }

    public function getFixtureBetweenTeams(int $homeTeamId, int $awayTeamId): Fixture
    {
        try {
            $fixture = Fixture::query()
                ->where('home_id', $homeTeamId)
                ->where('away_id', $awayTeamId)

                ->first();

            if (empty($fixture)) {
                throw new \Exception('Fixture not found');
            }

            return $fixture;

        } catch (\Exception $e) {
            throw new \Exception('Failed to get fixture by team: '.$e->getMessage());
        }
    }

    public function getNextWeekFixture(?int $week = null): Collection
    {
        try {
            if (is_null($week)) {
                $week = Fixture::where('is_played', true)->max('week') + 1;

                if (empty($week)) {
                    $week = 1;
                }
            }

            $fixtures = Fixture::with(['homeTeam', 'awayTeam'])
                ->where('is_played', false)
                ->where('week', '>=', $week)
                ->get();

            if (empty($fixtures)) {
                throw new \Exception('Fixtures not found');
            }

            return $fixtures;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get next week fixture: '.$e->getMessage());
        }
    }

    public function destroyAll(): void
    {
        Fixture::query()->delete();
        Matches::query()->delete();
        TeamStanding::query()->delete();
    }

    public function updateFixture(int $id, array $data)
    {
        try {
            $fixture = Fixture::find($id);

            if (empty($fixture)) {
                throw new \Exception('Fixture not found');
            }

            $fixture->update($data);

            return $fixture;
        } catch (\Exception $e) {
            throw new \Exception('Failed to update fixture: '.$e->getMessage());
        }
    }
}
