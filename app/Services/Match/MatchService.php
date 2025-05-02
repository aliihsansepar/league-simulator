<?php

namespace App\Services\Match;

use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Match\MatchRepositoryInterface;

class MatchService
{
    public function __construct(
        private MatchRepositoryInterface $matchRepository,
        private FixtureRepositoryInterface $fixtureRepository,
    ) {}

    public function getMatches()
    {
        return $this->matchRepository->getAll();
    }

    public function getMatchById(int $id)
    {
        return $this->matchRepository->getById($id);
    }

    public function createMatch(array $data)
    {
        return $this->matchRepository->updateOrCreate($data);
    }

    public function resetMatches(): void
    {
        $this->matchRepository->reset();
    }
}
