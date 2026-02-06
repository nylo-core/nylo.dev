<?php

namespace Tests\Unit\Services;

use App\Http\Services\GitHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubServiceTest extends TestCase
{
    use RefreshDatabase;

    private GitHubService $gitHubService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gitHubService = new GitHubService;
        Cache::flush();
    }

    /**
     * Test that getStars returns integer for valid repository.
     */
    public function test_get_stars_returns_integer(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/nylo' => Http::response([
                'stargazers_count' => 1234,
            ], 200),
        ]);

        $stars = $this->gitHubService->getStars('nylo');

        $this->assertIsInt($stars);
        $this->assertEquals(1234, $stars);
    }

    /**
     * Test that getStars returns null on API failure.
     */
    public function test_get_stars_returns_null_on_api_failure(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/invalid' => Http::response([], 404),
        ]);

        $stars = $this->gitHubService->getStars('invalid');

        $this->assertNull($stars);
    }

    /**
     * Test that getFormattedStars formats thousands correctly.
     */
    public function test_get_formatted_stars_formats_thousands(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/popular' => Http::response([
                'stargazers_count' => 1234,
            ], 200),
        ]);

        $formatted = $this->gitHubService->getFormattedStars('popular');

        $this->assertEquals('1.2K', $formatted);
    }

    /**
     * Test that getFormattedStars returns plain number for less than 1000.
     */
    public function test_get_formatted_stars_returns_plain_number_below_thousand(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/small' => Http::response([
                'stargazers_count' => 500,
            ], 200),
        ]);

        $formatted = $this->gitHubService->getFormattedStars('small');

        $this->assertEquals('500', $formatted);
    }

    /**
     * Test that getFormattedStars returns 0 on failure.
     */
    public function test_get_formatted_stars_returns_zero_on_failure(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/invalid' => Http::response([], 404),
        ]);

        $formatted = $this->gitHubService->getFormattedStars('invalid');

        $this->assertEquals('0', $formatted);
    }

    /**
     * Test that stars are cached.
     */
    public function test_stars_are_cached(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/cached' => Http::response([
                'stargazers_count' => 999,
            ], 200),
        ]);

        // First call should hit API
        $this->gitHubService->getStars('cached');

        // Verify it's cached
        $this->assertTrue(Cache::has('github_stars_cached'));

        // Change the fake response
        Http::fake([
            'api.github.com/repos/nylo-core/cached' => Http::response([
                'stargazers_count' => 1500,
            ], 200),
        ]);

        // Second call should return cached value
        $stars = $this->gitHubService->getStars('cached');

        $this->assertEquals(999, $stars);
    }

    /**
     * Test that getFormattedStars handles large numbers correctly.
     */
    public function test_get_formatted_stars_handles_large_numbers(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/mega' => Http::response([
                'stargazers_count' => 15000,
            ], 200),
        ]);

        $formatted = $this->gitHubService->getFormattedStars('mega');

        $this->assertEquals('15.0K', $formatted);
    }
}
