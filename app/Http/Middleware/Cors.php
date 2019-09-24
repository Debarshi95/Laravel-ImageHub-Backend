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
        $response = $next($request);
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Content-type", "application/json");
        $response->headers->set("Acess-Control-Allow-Methods", "GET,POST,PUT,PATCH, DELETE, OPTIONS");
        $response->headers->set("Access-Control-Allow-Headers", "X-Requested-With, Methods, Content-type, Authorization");
        return $response;
    }
}
