<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TeamStandings\StandingService;

class TeamStandingController extends Controller
{
    public function __construct(
        private StandingService $standingService
    ) {}

    public function index()
    {
        $standings = $this->standingService->getTeamStandings();

        return response()->json($standings);
    }
}
