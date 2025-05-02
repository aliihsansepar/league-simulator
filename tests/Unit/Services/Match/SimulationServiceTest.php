<?php

namespace Tests\Unit\Services\Match;

use App\Events\MatchCompleted;
use App\Models\Fixture;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Services\Match\SimulationService;
use App\Services\TeamStandings\StandingService;
use Illuminate\Support\Facades\Event;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class SimulationServiceTest extends TestCase
{
    private MockInterface|TeamRepositoryInterface $teamRepositoryMock;

    private MockInterface|FixtureRepositoryInterface $fixtureRepositoryMock;

    private MockInterface|StandingService $standingServiceMock;

    private SimulationService $simulationService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock dependencies
        $this->teamRepositoryMock = Mockery::mock(TeamRepositoryInterface::class);
        $this->fixtureRepositoryMock = Mockery::mock(FixtureRepositoryInterface::class);
        $this->standingServiceMock = Mockery::mock(StandingService::class);

        // Instantiate the service with mocks
        $this->simulationService = new SimulationService(
            $this->fixtureRepositoryMock,
            $this->teamRepositoryMock,
            $this->standingServiceMock
        );

        // Fake the event dispatcher
        Event::fake();
    }

    /**
     * Test that simulate method fetches teams and fixture, and dispatches MatchCompleted event.
     */
    public function test_simulate_dispatches_match_completed_event(): void
    {
        // 1. Arrange
        $homeTeamId = 1;
        $awayTeamId = 2;
        $teamsInput = ['homeTeamId' => $homeTeamId, 'awayTeamId' => $awayTeamId];

        // Create dummy models (no need to persist)
        $homeTeam = new Team(['id' => $homeTeamId, 'name' => 'Home Team', 'attack_strength' => 8, 'midfield_strength' => 7, 'defense_strength' => 6, 'goalkeeper_strength' => 7]);
        $awayTeam = new Team(['id' => $awayTeamId, 'name' => 'Away Team', 'attack_strength' => 6, 'midfield_strength' => 5, 'defense_strength' => 7, 'goalkeeper_strength' => 8]);
        $fixture = new Fixture(['id' => 10, 'home_id' => $homeTeamId, 'away_id' => $awayTeamId]);

        // Set up mock expectations
        $this->teamRepositoryMock
            ->shouldReceive('getTeamById')
            ->with($homeTeamId)
            ->once()
            ->andReturn($homeTeam);

        $this->teamRepositoryMock
            ->shouldReceive('getTeamById')
            ->with($awayTeamId)
            ->once()
            ->andReturn($awayTeam);

        $this->fixtureRepositoryMock
            ->shouldReceive('getFixtureBetweenTeams')
            ->with($homeTeamId, $awayTeamId)
            ->once()
            ->andReturn($fixture);

        // 2. Act
        $this->simulationService->simulate($teamsInput);

        // 3. Assert
        Event::assertDispatched(MatchCompleted::class, function (MatchCompleted $event) use ($fixture, $homeTeam, $awayTeam) {
            // Check if the event has the correct fixture, home team, and away team
            $this->assertSame($fixture, $event->fixture);
            $this->assertSame($homeTeam, $event->homeTeam);
            $this->assertSame($awayTeam, $event->awayTeam);

            // Check if goals are integers (we can't predict exact values due to rand())
            $this->assertIsInt($event->homeTeamGoals);
            $this->assertIsInt($event->awayTeamGoals);
            $this->assertGreaterThanOrEqual(0, $event->homeTeamGoals);
            $this->assertGreaterThanOrEqual(0, $event->awayTeamGoals);

            return true; // Indicate that the event matches the expectations
        });
    }

    protected function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery
        parent::tearDown();
    }
}
