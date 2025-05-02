<?php

namespace App\Repositories\Team;

interface TeamRepositoryInterface
{
    public function getTeams();

    public function getTeamById(int $id);

}