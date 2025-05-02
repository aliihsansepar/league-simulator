<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'home_team' => $this->home_team->name,
            'home_team_id' => $this->home_id,
            'home_team_short' => $this->home_team->short_name,
            'away_team' => $this->away_team->name,
            'away_team_id' => $this->away_id,
            'away_team_short' => $this->away_team->short_name,
            'match_date' => $this->match_date,
            'home_score' => $this->home_goals,
            'away_score' => $this->away_goals,
            'played' => (bool) $this->isPlayed,
            'week' => $this->fixture->week ?? null,
        ];
    }
}
