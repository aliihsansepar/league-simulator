<?php

namespace App\Services\Team;

use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\TeamStanding\TeamStandingRepositoryInterface;

class TeamService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
        private TeamStandingRepositoryInterface $teamStandingRepository
    ) {}

    public function getTeams()
    {
        return $this->teamRepository->getTeams();
    }

    public function getTeamById(int $id)
    {
        return $this->teamRepository->getTeamById($id);
    }

    public function getTeamStanding(int $teamId)
    {
        return $this->teamStandingRepository->getTeamStandingByTeamId($teamId);
    }
}
