<?php

namespace Spatie\Permission\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Guard;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        if (auth()->guard('admin')->check() || auth()->guard('web')->check()) {
            if(auth()->guard('web')->check()){
                $type = auth()->user()->user_type ?? ' ';
                if ($type == 'subadmin') {
                    $permissions = is_array($permission)
                        ? $permission
                        : explode('|', $permission);

                    foreach ($permissions as $permission) {
                        if ($authGuard->user()->can($permission)) {
                            return $next($request);
                        }
                    }

                    throw UnauthorizedException::forPermissions($permissions);
                } else {
                    return $next($request);
                }
            }
            else{
                return $next($request);
            }
        }

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
    }
}
