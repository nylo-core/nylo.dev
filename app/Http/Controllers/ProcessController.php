<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Log;

class ProcessController extends Controller
{
    public function siteUpdate(Request $request)
    {
        $token = $request->bearerToken();
        abort_if($token != config('project.meta.process_token'), 403);
        
        $result = Process::run('git pull && php artisan migrate --force && php artisan optimize');
        Log::info($result->output());
    }
}
