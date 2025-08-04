<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        
        $user = Auth::user();
        $permissions = $user->rolePermission->toArray();
        $route = $request->route()->getName();

        $permission = 0;
        foreach ($permissions as $item) {
            if (strpos($item['route'], $route . '.view') !== false && $item['permission'] == 1) {
                $permission = 1;
            }

            if (strpos($item['route'], 'edit') !== false && strpos($route, 'create') !== false && $item['permission'] == 1) {
                $permission = 1;
            }
            if (strpos($item['route'], 'edit') !== false && strpos($route, 'edit') !== false && $item['permission'] == 1) {
                $permission = 1;
            }
            if (strpos($item['route'], 'edit') !== false && strpos($route, 'save') !== false && $item['permission'] == 1) {
                $permission = 1;
            }

            if (strpos($item['route'], 'edit') !== false && strpos($route, 'change') !== false && $item['permission'] == 1) {
                $permission = 1;
            }

            if (strpos($item['route'], 'delete') !== false && $item['permission'] == 1) {
                $permission = 1;
            }
        };

        if ($permission) {
            return $next($request);
        } else if ($route == 'dashboard') {
            return $next($request);
        } else if ($route == 'filter') {
            return $next($request);
        } else if ($user->id == 1) {
            return $next($request);
        } else {
            return redirect()->route('permission');
        }
    }
}
