<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // pastikan tanpa slash di awal/akhir
        $p = trim(config('app.admin_prefix', 'admin'), '/');

        // jika akses ke /admin ATAU /admin/*
        if ($request->is($p) || $request->is($p.'/*') || $request->routeIs('admin.*')) {
            return route('admin.login');
        }

        // kalau kamu tidak punya login publik, lempar ke home saja
        return url('/');
    }
}
