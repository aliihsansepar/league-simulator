<?php

namespace Tests\Unit\Listeners;

use App\Events\MatchCompleted;
use App\Listeners\UpdateTeamStanding;
use App\Models\Fixture;
use App\Models\Team;
use App\Models\TeamStanding;
use App\Services\TeamStandings\StandingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UpdateTeamStandingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the listener correctly sorts standings based on points,
     * goal difference, and goals for.
     */
    public function test_standings_are_sorted_correctly(): void
    {
        $teamA = Team::factory()->name('Team A')->shortName('A')->create();
        $teamB = Team::factory()->name('Team B')->shortName('B')->create();
        $teamC = Team::factory()->name('Team C')->shortName('C')->create();
        $teamD = Team::factory()->name('Team D')->shortName('D')->create();
        $teamE = Team::factory()->name('Team E')->shortName('E')->create();

        $fixture = Fixture::factory()->create([
            'home_id' => $teamA->id,
            'away_id' => $teamB->id,
        ]);

        // Team A: 10 pts, +5 GD, 15 GF
        TeamStanding::factory()->create([
            'team_id' => $teamA->id,
            'points' => 10,
            'goals_for' => 15,
            'goals_against' => 10,
            'matches_played' => 5,
            'wins' => 3, 'draws' => 1, 'losses' => 1,
            'position' => 0,
        ]);
        // Team B: 12 pts, +8 GD, 20 GF
        TeamStanding::factory()->create([
            'team_id' => $teamB->id,
            'points' => 12,
            'goals_for' => 20,
            'goals_against' => 12,
            'matches_played' => 5,
            'wins' => 4, 'draws' => 0, 'losses' => 1,
            'position' => 0,
        ]);
        // Team C: 10 pts, +5 GD, 12 GF
        TeamStanding::factory()->create([
            'team_id' => $teamC->id,
            'points' => 10,
            'goals_for' => 12,
            'goals_against' => 7,
            'matches_played' => 5,
            'wins' => 3, 'draws' => 1, 'losses' => 1,
            'position' => 0,
        ]);
        // Team D: 7 pts, -2 GD, 8 GF
        TeamStanding::factory()->create([
            'team_id' => $teamD->id,
            'points' => 7,
            'goals_for' => 8,
            'goals_against' => 10,
            'matches_played' => 5,
            'wins' => 2, 'draws' => 1, 'losses' => 2,
            'position' => 0,
        ]);
        // Team E: 7 pts, -2 GD, 8 GF (Equal to D)
        TeamStanding::factory()->create([
            'team_id' => $teamE->id,
            'points' => 7,
            'goals_for' => 8,
            'goals_against' => 10,
            'matches_played' => 5,
            'wins' => 2, 'draws' => 1, 'losses' => 2,
            'position' => 0,
        ]);

        $standingServiceMock = Mockery::mock(StandingService::class);
        $standingServiceMock->shouldReceive('getTeamStandings')
            ->once()
            ->andReturn(TeamStanding::all());

        $event = new MatchCompleted($fixture, $teamA, $teamB, 1, 0);

        $listener = new UpdateTeamStanding($standingServiceMock);

        $listener->handle($event);

        $standings = TeamStanding::orderBy('position', 'asc')->get();

        $this->assertEquals($teamB->id, $standings[0]->team_id, 'Position 1 should be Team B (most points)');
        $this->assertEquals(1, $standings[0]->position);

        $this->assertEquals($teamA->id, $standings[1]->team_id, 'Position 2 should be Team A (equal points, better GF)');
        $this->assertTrue($standings[1]->position === 2, 'Position 2 check');

        $this->assertEquals($teamC->id, $standings[2]->team_id, 'Position 3 should be Team C (equal points, worse GF)');
        $this->assertTrue($standings[2]->position === 3, 'Position 3 check');

        $fourthPlaceTeam = $standings[3];
        $fifthPlaceTeam = $standings[4];

        $this->assertTrue($fourthPlaceTeam->position === 4, 'Position 4 rank check');
        $this->assertTrue($fifthPlaceTeam->position === 5, 'Position 5 rank check');

        $this->assertContains($fourthPlaceTeam->team_id, [$teamD->id, $teamE->id], 'Position 4 should be Team D or E');
        $this->assertContains($fifthPlaceTeam->team_id, [$teamD->id, $teamE->id], 'Position 5 should be Team D or E');
        $this->assertNotEquals($fourthPlaceTeam->team_id, $fifthPlaceTeam->team_id, 'Position 4 and 5 should be different teams');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
