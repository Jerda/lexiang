<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\AuthPermission;

class Permission
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
        $auth_permission = new AuthPermission();

        $can = $auth_permission->auth();

        if ($can === false) {
            return response()->json(['message' => trans('system.have_not_permission')], 409);
        }

        return $next($request);
    }
}
