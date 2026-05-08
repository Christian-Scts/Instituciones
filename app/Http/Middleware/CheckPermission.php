<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $permissions = session('admin_permissions', []);

        if (!session()->has('admin_user')) {
            return redirect()->route('admin.login');
        }

        if (!in_array($permission, $permissions, true)) {
                abort(403);
            }

        return $next($request);
    }
}