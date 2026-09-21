<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['admin', 'super_admin'])) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return $next($request);
                }
                return redirect()->route('admin.dashboard');
            }
        }
        
        return $next($request);
    }
}
