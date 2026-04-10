<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/admin');
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('/admin')->with('error', 'У вас нет прав администратора');
        }

        return $next($request);
    }
}