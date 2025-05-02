<?php

namespace App\Repositories\Team;

use App\Models\Team;

class TeamRepository implements TeamRepositoryInterface
{
    public function getTeams()
    {
        return Team::with(['homeFixtures', 'awayFixtures', 'standing'])->get();
    }

    public function getTeamById(int $id)
    {
        return Team::find($id);
    }
}
