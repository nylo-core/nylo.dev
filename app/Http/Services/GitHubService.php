<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Class GitHubService
 */
class GitHubService
{
    private const OWNER = 'nylo-core';

    /**
     * Get the star count for a repository.
     *
     * @param  string  $repoName  The repository name (e.g., 'nylo', 'support')
     */
    public function getStars(string $repoName): ?int
    {
        $cacheMinutes = config('project.meta.github_stars_cache_minutes', 60);

        return Cache::remember("github_stars_{$repoName}", now()->addMinutes($cacheMinutes), function () use ($repoName) {
            $response = Http::get('https://api.github.com/repos/'.self::OWNER."/{$repoName}");

            if (! $response->successful()) {
                return null;
            }

            return $response->json('stargazers_count');
        });
    }

    /**
     * Get formatted star count (e.g., "1.2K" for 1200).
     */
    public function getFormattedStars(string $repoName): string
    {
        $stars = $this->getStars($repoName);

        if ($stars === null) {
            return '0';
        }

        if ($stars >= 1000) {
            return number_format($stars / 1000, 1).'K';
        }

        return (string) $stars;
    }
}
