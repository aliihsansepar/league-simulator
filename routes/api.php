<?php

use App\Http\Controllers\API\FixtureController;
use App\Http\Controllers\API\MatchesController;
use App\Http\Controllers\API\SeasonsController;
use App\Http\Controllers\API\StandingsController;
use App\Http\Controllers\API\TeamsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('teams')->group(function () {
        Route::get('/', [TeamsController::class, 'index']);
        Route::get('/{team}', [TeamsController::class, 'show']);
        Route::post('/', [TeamsController::class, 'create']);
    });

    Route::prefix('seasons')->group(function () {
        Route::get('/', [SeasonsController::class, 'index']);
        Route::get('/{season}', [SeasonsController::class, 'show']);
        Route::post('/', [SeasonsController::class, 'create']);
        Route::put('/{season}', [SeasonsController::class, 'update']);
        Route::delete('/{season}', [SeasonsController::class, 'destroy']);
    });

    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchesController::class, 'index']);
        Route::get('/{match}', [MatchesController::class, 'show']);
        Route::post('/simulate', [MatchesController::class, 'simulate']);
        Route::post('/simulate-week', [MatchesController::class, 'simulateWeek']);
        Route::post('/simulate-all', [MatchesController::class, 'simulateAll']);
    });

    Route::group(['prefix' => 'fixtures'], function () {
        Route::post('/{season}', [FixtureController::class, 'seasonFixtures']);
        Route::post('/fixtures/generate', [FixtureController::class, 'generate']);

        Route::put('/{id}', [FixtureController::class, 'update']); // update a specific fixture
    });

    Route::prefix('standings')->group(function () {
        Route::get('/{season}', [StandingsController::class, 'seasonStandings']);
        Route::get('/{season}/week/{week}', [StandingsController::class, 'weekStandings']);
        Route::get('/{season}/team/{team}', [StandingsController::class, 'teamStandings']);
        Route::get('/{season}/team/{team}/week/{week}', [StandingsController::class, 'teamWeekStandings']);

    });
});
