<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $allowedOrigins = ['*']; // Adjust this to your allowed origins

        // if (in_array($request->header('Origin'), $allowedOrigins)) {
        //     return $next($request)
        //         ->header('Access-Control-Allow-Origin', $request->header('Origin'))
        //         ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        //         ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        // }

        // // Handle the preflight OPTIONS request
        // if ($request->isMethod('OPTIONS')) {
        //     return response()->json('', 204)
        //         ->header('Access-Control-Allow-Origin', $request->header('Origin'))
        //         ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        //         ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        // }

        // return abort(403, 'Unauthorized');
    }
}
