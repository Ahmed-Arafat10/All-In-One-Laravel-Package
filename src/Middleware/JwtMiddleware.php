<?php

namespace AhmedArafat\AllInOne\Middleware;

use AhmedArafat\AllInOne\Traits\ApiResponser;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, $guard = 'user'): Response
    {
        $user = request()->user($guard);
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->showMessage(null, __("Token Is Invalid"), 422);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->showMessage(null, __("Token Is Expired"), 422);
            } else {
                return $this->showMessage(null, __("Unauthenticated User"), 422);
            }
        }
        if (!$user) return $this->showMessage(null, __("Unauthenticated User #2"), 422);
        Auth::shouldUse($guard);
        return $next($request);
    }
}
