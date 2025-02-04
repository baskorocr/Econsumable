<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // Simpan URL yang ingin diakses sebelum redirect
            session()->put('url.intended', $request->fullUrl());
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (in_array($user->idRole, $roles)) {
            Auth::user()->load('role');
            return $next($request);
        }

        return redirect()->back()->with('error', 'You do not have access to this page.');
    }
}