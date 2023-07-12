<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PackageService;
use App\Http\Requests\GitHubVersionRequest;
use Log;

/**
 * Class GitHubActionsController
 *
 * @property PackageService $packageService
 * @package App\Htpp\Controllers\GitHubActionsController
 */
class GitHubActionsController extends Controller
{
    function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * GitHub Workflow http request to update the latest version of a package.
     *
     * @return Response
     */
    public function version(GitHubVersionRequest $request, $repository)
    {
        $didSucceed = $this->packageService->updateVersion($repository, $request->version);
        if (!$didSucceed) {
            return response()->json(['status' => 'failed', 'error_code' => 539]);
        }

        return response()->json(['status' => 'success', 'error_code' => 0]);
    }

    /**
     * Webhook for Release event.
     *
     * @return Response
     */
    public function release(Request $request)
    {
        if ($request->action != 'released') {
            Log::debug('GitHubActionsController@release received a wrong response action. ' . json_encode($request->input()));
            return response()->json(['status' => 'failed'], 422);
        }

        $releaseData = $request->release;
        
        if ($releaseData['draft'] != false) {
            Log::debug('GitHubActionsController@release received payload in draft. ' . json_encode($request->input()));
            return response()->json(['status' => 'failed'], 422);
        }

        $tagName = $releaseData['tag_name'];
        
        $repositoryData = $request->repository;
        $repositoryName = $repositoryData['name'];

        $didSucceed = $this->packageService->updateVersion($repositoryName, $tagName);
        if (!$didSucceed) {
            return response()->json(['status' => 'failed', 'error_code' => 539]);
        }

        return response()->json(['status' => 'success']);
    }
}
