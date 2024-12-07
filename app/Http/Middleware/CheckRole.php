<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::guard($role)->check()) {
            if ($role == 'admin') {
                return redirect('/admin/login');
            } elseif ($role == 'vendor') {
                return redirect('/vendor/login');
            } else {
                return redirect('/login');
            }
        }
        return $next($request);
    }
}
