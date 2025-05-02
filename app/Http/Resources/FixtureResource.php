<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FixtureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $formattedFixture = [];

        foreach ($this->resource as $week => $matches) {
            $formattedFixture[$week] = $this->formatMatches($matches->resource);
        }

        return $formattedFixture;
    }

    /**
     * Format the matches data
     */
    private function formatMatches($matches): array
    {
        $formattedMatches = [];
        foreach ($matches as $fixture) {

            $formattedMatches[] = [
                'id' => $fixture['id'],
                'homeTeam' => $fixture['homeTeam']['name'],
                'homeTeamId' => $fixture['home_id'],
                'homeTeamShort' => $fixture['homeTeam']['short_name'],
                'awayTeam' => $fixture['awayTeam']['name'],
                'awayTeamId' => $fixture['away_id'],
                'awayTeamShort' => $fixture['awayTeam']['short_name'],
                'date' => $fixture['date'],
                'homeScore' => $fixture['home_goals'] ?? 0,
                'awayScore' => $fixture['away_goals'] ?? 0,
                'played' => (bool) $fixture['is_played'],
                'week' => $fixture['week'],
            ];
        }

        return $formattedMatches;
    }
}
