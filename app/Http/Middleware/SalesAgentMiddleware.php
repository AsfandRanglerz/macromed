<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SalesAgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->guard('sales_agent')->check()) {
            return $next($request);
        } else {
            return redirect('sales-agent')->with(['alert' => 'error', 'error' => 'Unauthorized access!']);
        }
    }
}
