<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $subdomain = explode('.', $request->getHost())[0];
    
        // Query the database or another storage mechanism to identify the hospital based on the subdomain
        $hospital = Hospital::where('subdomain', $subdomain)->first();
    
        if (!$hospital) {
            abort(404); // Hospital not found
        }
    
        // Store the hospital information in the request for later use
        $request->merge(['hospital' => $hospital]);
    
        return $next($request);
    }
}
