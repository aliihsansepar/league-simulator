<?php

use App\Http\Controllers\API\FixtureController;
use App\Http\Controllers\API\MatchesController;
use App\Http\Controllers\API\TeamsController;
use App\Http\Controllers\API\TeamStandingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the API',
            'version' => '1.0.0',
            'status' => 'success',
        ]);
    });

    Route::group(['prefix' => 'fixtures'], function () {
        Route::get('/', [FixtureController::class, 'index'])->name('fixtures.index');
        Route::get('/{week}', [FixtureController::class, 'show'])
            ->where('week', '[0-9]+')
            ->name('fixtures.show');
        Route::post('/generate', [FixtureController::class, 'generate'])->name('fixtures.generate');
        Route::post('/reset', [FixtureController::class, 'reset'])->name('fixtures.reset');
        Route::get('/predictions', [FixtureController::class, 'predictions'])->name('fixtures.predictions');
    });

    Route::prefix('teams')->group(function () {
        Route::get('/', [TeamsController::class, 'index'])->name('teams.index');
        Route::get('/{team}', [TeamsController::class, 'show'])->name('teams.show');
    });

    Route::prefix('standings')->group(function () {
        Route::get('/', [TeamStandingController::class, 'index'])->name('standings.index');
    });

    Route::prefix('matches')->group(function () {
        Route::post('/simulate', [MatchesController::class, 'simulate'])->name('matches.simulate');
        Route::post('/simulate-week', [MatchesController::class, 'simulateWeek'])->name('matches.simulateWeek');
        Route::post('/simulate-season', [MatchesController::class, 'simulateSeason'])->name('matches.simulateSeason');
        Route::post('/reset', [MatchesController::class, 'reset'])->name('matches.reset');
    });
});
