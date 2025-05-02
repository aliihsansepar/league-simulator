<?php

namespace Tests\Feature;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching fixtures for a specific week.
     *
     * @return void
     */
    public function test_can_show_fixtures_for_a_week()
    {
        // Seed teams and generate fixtures for testing
        Team::factory()->count(4)->create();

        // Assume fixtures are generated for week 1
        $response = $this->callApi('GET', route('fixtures.show', ['week' => 1]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // Assuming multiple matches per fixture
                        'id',
                        'week',
                        'homeTeamId',
                        'awayTeamId',
                        'homeTeamScore',
                        'awayTeamScore',
                        'playedAt',
                        'homeTeam' => [
                            'id', 'name', 'strength',
                        ],
                        'awayTeam' => [
                            'id', 'name', 'strength',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test fetching fixtures for a non-existent week.
     *
     * @return void
     */
    public function test_returns_not_found_for_non_existent_week()
    {
        $response = $this->callApi('GET', route('fixtures.show', ['week' => 99]));

        $response->assertStatus(404);
    }

    /**
     * Test fetching fixtures with invalid week format.
     *
     * @return void
     */
    public function test_returns_not_found_for_invalid_week_format()
    {
        $response = $this->callApi('GET', route('fixtures.show', ['week' => 'invalid-week']));

        $response->assertStatus(404); // Route model binding or validation should handle this
    }

    /**
     * Test generating fixtures.
     *
     * @return void
     */
    public function test_can_generate_fixtures()
    {
        Team::factory()->count(4)->create(); // Need teams to generate fixtures

        $initialFixtureCount = Fixture::count();

        $response = $this->callApi('POST', route('fixtures.generate'));

        $response->assertCreated()
            ->assertJson([
                'message' => 'Fixtures generated successfully.',
            ]);

        // Assert that fixtures were actually created
        $this->assertGreaterThan($initialFixtureCount, Fixture::count());
    }

    /**
     * Test generating fixtures when teams are not enough.
     *
     * @return void
     */
    public function test_cannot_generate_fixtures_with_insufficient_teams()
    {
        Team::factory()->count(1)->create(); // Not enough teams

        $response = $this->callApi('POST', route('fixtures.generate'));

        // Assuming the controller returns an error status code, e.g., 422 or 400
        $response->assertStatus(422); // Or 400 Bad Request
        // Optionally check for a specific error message
        // $response->assertJsonValidationErrorFor('teams');
        // $response->assertJson(['message' => 'Error message']);
    }

    private function callApi($method, $url, $data = [])
    {
        return $this->json($method, $url, $data);
    }
}
