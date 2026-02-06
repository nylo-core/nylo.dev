<?php

namespace Tests\Unit\Services;

use App\Http\Services\PackageService;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PackageServiceTest extends TestCase
{
    use RefreshDatabase;

    private PackageService $packageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->packageService = new PackageService;
    }

    /**
     * Test that repositoryUrl generates correct URL.
     */
    public function test_repository_url_generates_correct_url(): void
    {
        $package = Package::factory()->create([
            'organization' => 'nylo-core',
            'repository' => 'support',
        ]);

        $url = $this->packageService->repositoryUrl($package);

        $this->assertEquals('https://github.com/nylo-core/support', $url);
    }

    /**
     * Test that pubDevUrl generates correct URL.
     */
    public function test_pub_dev_url_generates_correct_url(): void
    {
        $package = Package::factory()->create([
            'repository' => 'nylo-support',
        ]);

        $url = $this->packageService->pubDevUrl($package);

        $this->assertEquals('https://pub.dev/packages/nylo-support', $url);
    }

    /**
     * Test that releaseNoteUrl generates correct URL.
     */
    public function test_release_note_url_generates_correct_url(): void
    {
        $package = Package::factory()->create([
            'organization' => 'nylo-core',
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        $url = $this->packageService->releaseNoteUrl($package);

        $this->assertEquals('https://github.com/nylo-core/support/releases/tag/v5.0.0', $url);
    }

    /**
     * Test that getResourceMetaData returns array with package data.
     */
    public function test_get_resource_metadata_returns_array(): void
    {
        Package::factory()->create([
            'organization' => 'nylo-core',
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        Package::factory()->create([
            'organization' => 'nylo-core',
            'repository' => 'framework',
            'version' => '7.0.0',
        ]);

        Cache::forget('package-resource-md');

        $metadata = $this->packageService->getResourceMetaData();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('support', $metadata);
        $this->assertArrayHasKey('framework', $metadata);
        $this->assertEquals('nylo-core', $metadata['support']['organization']);
        $this->assertEquals('5.0.0', $metadata['support']['version']);
    }

    /**
     * Test that getResourceMetaData is cached.
     */
    public function test_get_resource_metadata_is_cached(): void
    {
        Package::factory()->create([
            'organization' => 'nylo-core',
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        Cache::forget('package-resource-md');

        // First call should cache
        $this->packageService->getResourceMetaData();

        $this->assertTrue(Cache::has('package-resource-md'));
    }

    /**
     * Test that updateVersion updates package version.
     */
    public function test_update_version_updates_package_version(): void
    {
        $package = Package::factory()->create([
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        $this->packageService->updateVersion('support', 'v6.0.0');

        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'version' => '6.0.0',
        ]);
    }

    /**
     * Test that updateVersion clears cache.
     */
    public function test_update_version_clears_cache(): void
    {
        $package = Package::factory()->create([
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        // Populate cache
        $this->packageService->getResourceMetaData();
        $this->assertTrue(Cache::has('package-resource-md'));

        // Update version should clear cache
        $this->packageService->updateVersion('support', 'v6.0.0');

        $this->assertFalse(Cache::has('package-resource-md'));
    }

    /**
     * Test that updateVersion strips v prefix from version.
     */
    public function test_update_version_strips_v_prefix(): void
    {
        $package = Package::factory()->create([
            'repository' => 'support',
            'version' => '5.0.0',
        ]);

        $this->packageService->updateVersion('support', 'v6.0.0');

        $package->refresh();
        $this->assertEquals('6.0.0', $package->version);
    }
}
