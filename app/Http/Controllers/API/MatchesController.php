<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimulateMatchRequest;
use App\Http\Requests\SimulateWeekRequest;
use App\Services\Match\MatchService;
use App\Services\Match\SimulationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MatchesController extends Controller
{
    public function __construct(
        private SimulationService $simulationService,
        private MatchService $matchService
    ) {}

    public function simulate(SimulateMatchRequest $request)
    {
        return $this->simulationService->simulate($request->validated());
    }

    public function simulateWeek(SimulateWeekRequest $request)
    {
        return $this->simulationService->simulateWeek($request->validated());
    }

    public function simulateSeason(Request $request)
    {
        return $this->simulationService->simulateSeason();
    }

    public function reset()
    {
        try {
            $this->matchService->resetMatches();

            return response()->json([
                'message' => 'Matches reset successfully',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
