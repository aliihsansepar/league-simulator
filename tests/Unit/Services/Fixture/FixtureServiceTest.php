<?php

namespace Tests\Unit\Services\Fixture;

use App\Events\FixtureGenerated;
use App\Models\Team;
use App\Repositories\Fixture\FixtureRepositoryInterface;
use App\Services\Fixture\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class FixtureServiceTest extends TestCase
{
    use RefreshDatabase;

    private FixtureRepositoryInterface $fixtureRepositoryMock;

    private FixtureService $fixtureService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixtureRepositoryMock = Mockery::mock(FixtureRepositoryInterface::class);
        $this->fixtureService = new FixtureService($this->fixtureRepositoryMock);

        Event::fake();
    }

    public function test_can_generate_fixtures_for_even_number_of_teams()
    {
        Team::factory()->count(4)->create();

        /** @var \Mockery\MockInterface $fixtureRepositoryMock */
        $fixtureRepositoryMock = $this->fixtureRepositoryMock;
        $fixtureRepositoryMock
            ->shouldReceive('createFixtures')
            ->once()
            ->withArgs(function ($fixtures) {
                return is_array($fixtures) && count($fixtures) === 12;
            });

        $generatedFixtures = $this->fixtureService->generateFixtures();

        $this->assertCount(12, $generatedFixtures);
        Event::assertDispatched(FixtureGenerated::class);
    }

    public function test_can_generate_fixtures_for_odd_number_of_teams()
    {
        Team::factory()->count(5)->create();

        /** @var \Mockery\MockInterface $fixtureRepositoryMock */
        $fixtureRepositoryMock = $this->fixtureRepositoryMock;
        $fixtureRepositoryMock
            ->shouldReceive('createFixtures')
            ->once()
            ->withArgs(function ($fixtures) {
                return is_array($fixtures) && count($fixtures) === 20;
            });

        $generatedFixtures = $this->fixtureService->generateFixtures();

        $this->assertCount(20, $generatedFixtures);
        Event::assertDispatched(FixtureGenerated::class);
    }

    public function test_it_throws_exception_if_repository_fails()
    {
        Team::factory()->count(4)->create();

        /** @var \Mockery\MockInterface $fixtureRepositoryMock */
        $fixtureRepositoryMock = $this->fixtureRepositoryMock;
        $fixtureRepositoryMock
            ->shouldReceive('createFixtures')
            ->once()
            ->andThrow(new \Exception('Failed to create fixtures'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to create fixtures');

        $this->fixtureService->generateFixtures();

        Event::assertNotDispatched(FixtureGenerated::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
