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
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = ['*']; // Replace with your allowed origins

        if (in_array($request->server('HTTP_ORIGIN'), $allowedOrigins)) {
            $response = $next($request);
            $response->header('Access-Control-Allow-Origin', $request->server('HTTP_ORIGIN'));
            $response->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS'); // Adjust allowed methods
            $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With'); // Adjust allowed headers
            return $response;
        }

        return abort(403); // Or return a more specific error response
    }
}
