<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Process;
use Artisan;
use Log;

class ProcessController extends Controller
{
    /**
     * Process to update the project.
     *
     * @return Response
     */
    public function siteUpdate(Request $request)
    {
        $token = $request->bearerToken();
        abort_if($token != config('project.meta.process_token'), 403);
        
        $processGitPull = Process::run(['git', 'pull']);
        logger('git pull');
        logger($processGitPull->errorOutput());;
        $exitCodeMigrate = Artisan::call('migrate', ['--force' => true]);
        logger('migrate');
        logger($exitCodeMigrate);
        $exitCodeOptimize = Artisan::call('optimize');
        logger('optimize');
        logger($exitCodeOptimize);
        
        if (($exitCodeMigrate + $exitCodeOptimize) != 0) {
            return response()->json(['status' => 'failed', 'error_code' => 19]);
        }

        return response()->json(['status' => 'ok', 'error_code' => 0]);
    }
}
