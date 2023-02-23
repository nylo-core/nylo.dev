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
     * @param String $page
     * @return String
     */
    public function getLastestVersionNylo() : String
    {
        return array_key_last(config('project.doc-index')['versions']);
    }

    /**
     * Performs a check to see if doc version is old.
     *
     * @param String $page
     * @return bool
     */
    public function isViewingOldDocs($version) : bool
    {
        $latestVersionOfNylo = $this->getLastestVersionNylo();
        return $latestVersionOfNylo != $version;
    }

    /**
     * Finds the correct section that a page belongs to.
     *
     * @param String $version
     * @param String $page
     * @return String
     */
    public function findDocSection($version, $page) : String
    {
        foreach (config('project.doc-index')['versions'][$version] as $key => $docLink) {
            if (in_array($page, $docLink)) {
                return $key;
            }
        }
        return '';

    }

    /**
     * Checks if the docs page exists in the resource path and then returns the path.
     *
     * @param String $version
     * @param String $page
     * @return String
     */
    public function checkIfDocExists($version, $page) : String
    {
        $mdDocPage = base_path() . '/resources/docs/' . $version . '/' . $page . '.md';
        abort_if(file_exists($mdDocPage) == false, 404);
        return $mdDocPage;
    }

    /**
     * Returns the zipball_url to download a project from GitHub.
     *
     * @param String $project
     * @return String
     */
    public function downloadFile($project) : String
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
     * @param String $version
     * @param String $page
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