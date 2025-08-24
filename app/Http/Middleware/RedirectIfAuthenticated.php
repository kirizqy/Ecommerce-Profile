<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check()) {
            $p = trim(config('app.admin_prefix', 'admin'), '/');

            // user sudah login lalu buka area admin/login
            if ($request->is($p) || $request->is($p.'/*') || $request->routeIs('admin.*')) {
                // kalau admin → dashboard admin
                if ((bool) optional(Auth::user())->is_admin) {
                    return redirect()->route('admin.dashboard');
                }
                // non-admin → lempar ke home (atau logout kalau mau)
                return redirect()->route('home');
            }

            // selain itu → home
            return redirect()->route('home');
        }

        return $next($request);
    }
}
