<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
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
        
        $result = Process::run('git pull && php artisan migrate --force && php artisan optimize');
        
        if (!$result->successful()) {
            return response()->json(['status' => 'failed', 'error_code' => 19]);
        }

        return response()->json(['status' => 'ok', 'error_code' => 0]);
    }
}
