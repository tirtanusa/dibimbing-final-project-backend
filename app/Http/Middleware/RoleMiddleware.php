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

    public function handle($request, Closure $next, string ...$roles)
    {
        if (!Auth::check()) {
            return $this->unauthorizedResponse();
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return $this->unauthorizedResponse();
        }

        return $next($request);
    }
}
