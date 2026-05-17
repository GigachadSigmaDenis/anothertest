<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ZamDirectorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!in_array(auth()->user()->role, ['zam_dir', 'admin'])) {
            abort(403, 'Доступ разрешён только заместителю директора');
        }

        return $next($request);
    }
}