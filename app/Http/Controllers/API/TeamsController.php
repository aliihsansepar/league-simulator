<?php

namespace App\Http\Controllers\API;

use App\Models\Team;

class TeamsController extends Controller
{
    public function index()
    {
        return response()->json(Team::all());
    }

    public function show(Team $team)
    {
        return response()->json($team);
    }
}
