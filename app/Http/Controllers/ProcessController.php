<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class ProcessController extends Controller
{
    /**
     * Process to update the project.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function siteUpdate(Request $request)
    {
        $gitReset = Process::run(['git', 'reset', '--hard']);
        $processGitPull = Process::run(['git', 'pull']);
        $exitCodeMigrate = Artisan::call('migrate', ['--force' => true]);
        $exitCodeOptimize = Artisan::call('optimize');

        if (($exitCodeMigrate + $exitCodeOptimize) != 0) {
            return response()->json(['status' => 'failed', 'error_code' => 19]);
        }

        return response()->json(['status' => 'ok', 'error_code' => 0]);
    }
}
