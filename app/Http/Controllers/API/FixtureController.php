<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateFixtureRequest;
use App\Http\Resources\FixtureCollection;
use App\Services\Fixture\ChampionPredictionService;
use App\Services\Fixture\FixtureService;
use Illuminate\Http\Response;

class FixtureController extends Controller
{
    public function __construct(
        private FixtureService $fixtureService,
        private ChampionPredictionService $championPredictionService
    ) {}

    public function index()
    {
        $fixtures = $this->fixtureService->getFixturesGroupedByWeek();

        $data = FixtureCollection::make($fixtures);

        return response()->json([
            'message' => 'Fixtures retrieved successfully',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function show(int $week)
    {
        $fixture = $this->fixtureService->getFixtureByWeek($week);

        return response()->json([
            'message' => 'Fixture retrieved successfully',
            'data' => $fixture,
        ], Response::HTTP_OK);
    }

    public function generate(CreateFixtureRequest $request)
    {
        $data = $this->fixtureService->generateFixtures();

        return response()->json([
            'data' => $data,
            'message' => 'Fixtures generated successfully',
        ], Response::HTTP_CREATED);
    }

    public function reset()
    {
        $this->fixtureService->reset();

        return response()->json([
            'message' => 'Fixtures reset successfully',
        ], Response::HTTP_OK);
    }

    public function predictions()
    {
        $predictions = $this->championPredictionService->calculate();
        
        return response()->json([
            'message' => 'Predictions retrieved successfully',
            'data' => $predictions,
        ], Response::HTTP_OK);
    }
}
