<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiToken
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
        if(!$request->has('api_key') && $request->api_key!="A2FVqDPFbr+zFLUigHPoTwMvKjLSm7YFaKpJX8M"){
            return response()->json(['status'=>false,'message'=>"Please provide api key to access the resource"],200);
        }
        return $next($request);
    }
}
