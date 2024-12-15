<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReferer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedDomain = env('ITCCTV_SERVER') ;
        $referer = $request->headers->get('REMOTE_ADDR');
       
        // dd($request);
        if ($referer && strpos($referer, $allowedDomain) === false) {
            return response('Forbidden', 403);
        }

        // if ($origin && strpos($origin, $allowedDomain) === false) {
        //     return response('Forbidden', 403);
        // }

        return $next($request);
    }
}
