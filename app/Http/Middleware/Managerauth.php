<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Managerauth  
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    
     public function handle($request, Closure $next)
     {
         if (!$this->isAdmin($request)) {
             abort(Response::HTTP_UNAUTHORIZED);
         }
 
         return $next($request);
     }
 
     protected function isAdmin($request)
     {
         // Write your logic to check if the user us admin.
 
         // something like
         $flag = 0;
         if($request->user()->role=='admin'|| $request->user()->role=='manager'|| $request->user()->role=='vendor')
            $flag = 1;
         return $flag;
     }
}
