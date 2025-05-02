<?php

namespace App\Listeners;

use App\Events\MatchCompleted;
use App\Models\TeamStanding;
use App\Services\TeamStandings\StandingService;

class UpdateTeamStanding
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private StandingService $standingService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchCompleted $event): void
    {
        $homeTeam = $event->homeTeam;
        $awayTeam = $event->awayTeam;

        $homeTeamGoals = $event->homeTeamGoals;
        $awayTeamGoals = $event->awayTeamGoals;

        $homeTeamStanding = TeamStanding::where('team_id', $homeTeam->id)->first();
        $awayTeamStanding = TeamStanding::where('team_id', $awayTeam->id)->first();

        $homeTeamStanding->matches_played += 1;
        $awayTeamStanding->matches_played += 1;
        if ($homeTeamGoals > $awayTeamGoals) {
            $homeTeamStanding->wins += 1;
            $homeTeamStanding->points += 3;

            $awayTeamStanding->losses += 1;
        } elseif ($homeTeamGoals < $awayTeamGoals) {
            $awayTeamStanding->wins += 1;
            $awayTeamStanding->points += 3;

            $homeTeamStanding->losses += 1;
        } else {
            $homeTeamStanding->draws += 1;
            $homeTeamStanding->points += 1;

            $awayTeamStanding->draws += 1;
            $awayTeamStanding->points += 1;
        }

        $homeTeamStanding->goals_for += $homeTeamGoals;
        $homeTeamStanding->goals_against += $awayTeamGoals;
        $homeTeamStanding->goal_difference = $homeTeamStanding->goals_for - $homeTeamStanding->goals_against;
        $homeTeamStanding->save();

        $awayTeamStanding->goals_for += $awayTeamGoals;
        $awayTeamStanding->goals_against += $homeTeamGoals;
        $awayTeamStanding->goal_difference = $awayTeamStanding->goals_for - $awayTeamStanding->goals_against;
        $awayTeamStanding->save();

        $standings = $this->standingService->getTeamStandings();

        // Sort standings based on points, goal difference, and goals for
        $sortedStandings = $standings->sort(function ($a, $b) {
            // 1. Points (desc)
            if ($a->points !== $b->points) {
                return $b->points <=> $a->points;
            }

            // 2. Goal Difference (desc)
            $goalDiffA = $a->goals_for - $a->goals_against;
            $goalDiffB = $b->goals_for - $b->goals_against;
            if ($goalDiffA !== $goalDiffB) {
                return $goalDiffB <=> $goalDiffA;
            }

            // 3. Goals For (desc)
            if ($a->goals_for !== $b->goals_for) {
                return $b->goals_for <=> $a->goals_for;
            }

            // Teams are equal based on these criteria for now
            // Head-to-head comparison would require fetching match results between A and B
            return 0;
        });

        // Assign positions based on the sorted order
        $rank = 1;
        $sortedStandings->values()->each(function ($standing) use (&$rank) {
            $standing->position = $rank++;
            $standing->save();
        });
    }
}
