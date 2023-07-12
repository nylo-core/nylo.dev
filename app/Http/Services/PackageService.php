<?php

namespace App\Http\Services;

use App\Models\Package;
use Cache;

/**
 * Class PackageService
 *
 * @package App\Http\Services\PackageService
 */
class PackageService
{

    /**
     * PackageService constructor
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the resource meta data.
     *
     * @return mixed
     */
    public function getResourceMetaData()
    {
        return Cache::rememberForever('package-resource-md', function () {
            $packages = Package::all();
            $payload = [];

            foreach ($packages as $package) {
                $payload[$package->repository] = [
                    'repository' => $package->repository,
                    'version' => $package->version,
                    'organization' => $package->organization,
                    'release_note_url' => $this->releaseNoteUrl($package),
                    'repository_url' => $this->repositoryUrl($package)
                ];
            }

            return $payload;
        });
    }

    /**
     * Get the repository url
     *
     * @return String
     */
    public function repositoryUrl(Package $package) : string
    {
        return "https://github.com/{$package->organization}/{$package->repository}";
    }

    /**
     * Get the Pub dev url
     *
     * @return String
     */
    public function pubDevUrl(Package $package) : string
    {
        return "https://pub.dev/packages/{$package->repository}";
    }

    /**
     * Get the release note url
     *
     * @return String
     */
    public function releaseNoteUrl(Package $package) : string
    {
        return "https://github.com/{$package->organization}/{$package->repository}/releases/tag/v{$package->version}";
    }

    /**
     * Update the package version
     *
     * @return mixed
     */
    public function updateVersion($repository, $version)
    {
        $versionNumber = $this->cleanVersion($version);
        $package = Package::where('repository', $repository)
                        ->update([
                            'version' => $versionNumber
                        ]);

        // clear cache                        
        Cache::forget('package-resource-md'); 

        return $package;
    }

    /**
     * Clean the version tag e.g. 1.0.0
     * 
     * @param String $version
     *
     * @return String
     */
    private function cleanVersion($version)
    {
        return str_replace("v", "", $version);
    }
}