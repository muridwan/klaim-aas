<?php

namespace App\Http\Middleware;

use Brick\Math\BigInteger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $rolesid): Response
    {
        if(session()->has('is_logged') )
        {
            $userRoleId = session("user_role")["role_id"];
            // pecah role yang dikirim (misalnya "7|8")
            $allowedRoles = explode('|', $rolesid);
            if (in_array($userRoleId,$allowedRoles)) {
                return $next($request);
            }            
        }
        abort(403,"UnAuthorized");
    }
}
