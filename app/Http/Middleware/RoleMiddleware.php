<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    use ApiResponse;

    public function handle($request, Closure $next, $role)
    {

        //Allow only instructor to access
        if(Auth::check() && Auth::user()->role == $role){
            return $next($request);
        }

        return $this->unauthorizedResponse();
    }
}
