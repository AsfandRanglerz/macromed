<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AuthController;

class admin
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

        if (auth()->guard('admin')->check() || auth()->guard('web')->check()) {
            return $next($request);
        } else {
            return redirect('admin-login')->with(['alert' => 'error', 'error' => 'Unauthorized Access!']);
        }
    }
}
