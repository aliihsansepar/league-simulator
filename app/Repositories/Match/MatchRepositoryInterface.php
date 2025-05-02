<?php

namespace App\Repositories\Match;

interface MatchRepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function updateOrCreate(array $data);

    public function reset();
}
