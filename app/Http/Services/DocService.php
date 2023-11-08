<?php

namespace App\Http\Services;

use App\Models\Download;
use Illuminate\Support\Facades\Http;
use Request;

/**
 * Class DocService
 *
 * @package App\Http\Services\DocService
 */
class DocService
{

   /**
     * DocService constructor
     *
     * @return void
     */
   public function __construct()
   {

   }

    /**
     * Get latest version for Nylo.
     *
     * @param string $page
     * @return string
     */
    public function getLastestVersionNylo() : string
    {
        return array_key_last(config('project.doc-index')['versions']);
    }

    /**
     * Performs a check to see if doc version is old.
     *
     * @param string $page
     * @return bool
     */
    public function isViewingOldDocs($version) : bool
    {
        $latestVersionOfNylo = $this->getLastestVersionNylo();
        return $latestVersionOfNylo != $version;
    }

    /**
     * Finds the correct section that a page belongs too.
     *
     * @param string $version
     * @param string $page
     * @return string
     */
    public function findDocSection($version, $page) : string
    {
        foreach (config('project.doc-index')['versions'][$version] as $key => $docLink) {
            if (in_array($page, $docLink)) {
                return $key;
            }
        }
        return '';
    }

    /**
     * Finds the correct Tutorial section that a page belongs too.
     *
     * @param string $version
     * @param string $page
     * @return string
     */
    public function findTutorialSection($version, $page) : string
    {
        foreach (config('project.doc-tutorials')['versions'][$version] as $key => $docLink) {
            $docLabels = collect($docLink)->map(function ($doc) {
                return $doc['label'];
            })->toArray();
            if (in_array($page, $docLabels)) {
                return $key;
            }
        }
        return '';
    }

     /**
     * Checks if doc-tutorials contains a valid $version
     *
     * @param string $version
     */
    public function containsTutorialsForVersion($version)
    {
        abort_if(array_key_exists($version, config('project.doc-tutorials')['versions']) == false, 403, "No tutorials found for $version");
    }

    /**
     * Checks if the docs page exists in the resource path and then returns the path.
     *
     * @param string $version
     * @param string $page
     * @return string
     */
    public function checkIfDocExists($version, $page) : string
    {
        $mdDocPage = base_path() . '/resources/docs/' . $version . '/' . $page . '.md';
        abort_if(file_exists($mdDocPage) == false, 404);
        return $mdDocPage;
    }

    /**
     * Checks if the tutorials docs page exists in the resource path and then returns the path.
     *
     * @param string $version
     * @param string $page
     * @return string
     */
    public function checkIfTutorialsExists($version, $page) : string
    {
        return 'docs/' . $version . '/tutorials/' . $page;
    }

    /**
     * Returns the tutorial meta data
     *
     * @param string $version
     * @param string $page
     * @return array
     */
    public function getTutorial($version, $page) : array
    {
        $docsIndex = config('project.doc-tutorials');
        $versions = $docsIndex['versions'][$version];
        foreach ($versions as $docs) {
            $results = collect($docs)->where('label', $page);
            if ($results->isEmpty()) {
                continue;
            }
            return collect($docs)->where('label', $page)->first();
        }
        return [];
    }

    /**
     * Returns the zipball_url to download a project from GitHub.
     *
     * @param string $project
     * @return string
     */
    public function downloadFile($project) : string
    {
        abort_if(!in_array($project, ['nylo-core/nylo']), 404);
        $response = Http::get("https://api.github.com/repos/" . $project . "/releases/latest");
        abort_if(!$response->successful(), 500);

        $download = Download::create([
            'project' => $project,
            'version' => $response->json('name'),
            'ip' => Request::ip()
        ]);
        abort_if(!$download, 500);

        return $response->json('zipball_url');
    }

    /**
     * Check if the docs contain a certain page.
     *
     * @param string $version
     * @param string $page
     * @return bool
     */
    public function checkDocsContainPage($version, $page) : bool
    {
        $docsIndex = config('project.doc-index');
        $versions = $docsIndex['versions'][$version];
        $versionArray = array_values($versions);
        $docValues = collect($versionArray)->flatten()->toArray();

        return in_array($page, $docValues);
    }
}
