<?php

namespace App\Providers;

use App\Repositories\Fixture\FixtureRepository;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Match\MatchRepository;
use App\Repositories\Match\MatchRepositoryInterface;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\TeamStanding\TeamStandingRepository;
use App\Repositories\TeamStanding\TeamStandingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FixtureRepositoryInterface::class, FixtureRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TeamStandingRepositoryInterface::class, TeamStandingRepository::class);
        $this->app->bind(MatchRepositoryInterface::class, MatchRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
