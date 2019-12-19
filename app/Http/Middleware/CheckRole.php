<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if($request->user()->roles->contains('name', $role)) {
                return $next($request);
            }
        }
        return abort(401);
    }
}
