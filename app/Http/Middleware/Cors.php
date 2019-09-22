<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)->header('Access-Control-Allow-Origin', '*')
            ->header("Content-type", "application/json")
            ->header("Acess-Control-Allow-methods", "GET,POST,PUT,PATCH, DELETE, OPTIONS")
            ->header("Access-Control-Allow-Headers", "X-Requested-With, Methods, Content-type, Authorization");
    }
}
