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
        //return $next($request)
        // ->header('Access-Control-Allow-Origin','*')
        // ->header('Access-Control-Allow-Methods','GET,POST,PUT,DLETE,PATCH,OPTIONS')
        // ->header('Access-Control-Allow-Headers','Access-Control-Allow-Headers','Conrent-type, Authorization, x-csrf-token, x-requested-with');
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,PATCH,OPTIONS',
            // 'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Headers' => 'X-REQUESTED-WITH',
            'Access-Control-Expose-Headers' => '*'
        ]; //allow return token in headers

        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
