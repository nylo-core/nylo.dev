<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Log;

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
        try {
            // Specify the project root directory
            $projectRoot = base_path();
            
            // Run git reset
            $gitReset = Process::path($projectRoot)->run('git reset --hard');
            if ($gitReset->failed()) {
                Log::error('Git reset failed: ' . $gitReset->errorOutput());
                return response()->json(['status' => 'failed', 'error_code' => 10, 'message' => 'Git reset failed']);
            }
            
            // Run git pull
            $gitPull = Process::path($projectRoot)->run('git pull');
            if ($gitPull->failed()) {
                Log::error('Git pull failed: ' . $gitPull->errorOutput());
                return response()->json(['status' => 'failed', 'error_code' => 11, 'message' => 'Git pull failed']);
            }
            
            // Run migrations
            $exitCodeMigrate = Artisan::call('migrate', ['--force' => true]);
            if ($exitCodeMigrate !== 0) {
                Log::error('Migration failed with exit code: ' . $exitCodeMigrate);
                return response()->json(['status' => 'failed', 'error_code' => 12, 'message' => 'Migration failed']);
            }
            
            // Optimize the application
            $exitCodeOptimize = Artisan::call('optimize');
            if ($exitCodeOptimize !== 0) {
                Log::error('Optimization failed with exit code: ' . $exitCodeOptimize);
                return response()->json(['status' => 'failed', 'error_code' => 13, 'message' => 'Optimization failed']);
            }
            
            Log::info('Site update completed successfully');
            return response()->json(['status' => 'ok', 'error_code' => 0]);
            
        } catch (\Exception $e) {
            Log::error('Site update failed with exception: ' . $e->getMessage());
            return response()->json(['status' => 'failed', 'error_code' => 500, 'message' => $e->getMessage()]);
        }
    }
}
