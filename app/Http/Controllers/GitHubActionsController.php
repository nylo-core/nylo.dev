<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PackageService;
use App\Http\Requests\GitHubVersionRequest;

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
     * Update the latest version of a package.
     *
     * @return Response
     */
    public function version(GitHubVersionRequest $request, $repository)
    {
        $version = substr($request->version, 1);

        $didSucceed = $this->packageService->update($repository, $version);

        if (!$didSucceed) {
            return response()->json(['status' => 'failed', 'error_code' => 539]);
        }

        return response()->json(['status' => 'success', 'error_code' => 0]);
    }
}
