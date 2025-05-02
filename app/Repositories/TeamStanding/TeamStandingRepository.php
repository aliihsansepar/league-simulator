<?php

namespace App\Repositories\TeamStanding;

use App\Models\TeamStanding;

class TeamStandingRepository implements TeamStandingRepositoryInterface
{
    public function getAll()
    {
        return TeamStanding::with('team')->orderBy('position', 'asc')->get();
    }

    public function getByTeamId(int $teamId)
    {
        return TeamStanding::with('team')->where('team_id', $teamId)->first();
    }

    public function create(array $data)
    {
        return TeamStanding::create($data);
    }

    public function update(int $teamId, array $data)
    {
        $teamStanding = $this->getByTeamId($teamId);

        if (! $teamStanding) {
            throw new \Exception('Team standing not found');
        }

        return $teamStanding->update($data);
    }

    public function truncate()
    {
        return TeamStanding::truncate();
    }
}
