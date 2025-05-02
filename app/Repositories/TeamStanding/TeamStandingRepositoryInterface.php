<?php

namespace App\Repositories\TeamStanding;

interface TeamStandingRepositoryInterface
{
    public function getAll();

    public function getByTeamId(int $teamId);

    public function create(array $data);

    public function update(int $teamId, array $data);

    public function truncate();
}
