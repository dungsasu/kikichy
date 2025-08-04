<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoLogout
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
        // Kiểm tra nếu user đã đăng nhập
        if (Auth::guard('members')->check()) {
            $sessionLifetime = config('session.lifetime') * 60; // Convert to seconds
            $lastActivity = session('lastActivityTime', time());
            $currentTime = time();
            
            // Kiểm tra nếu session đã hết hạn
            if (($currentTime - $lastActivity) > $sessionLifetime) {
                // Logout user
                Auth::guard('members')->logout();
                
                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect về trang chủ với thông báo
                return redirect()->route('client.home.index')
                    ->with('warning', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
            }
            
            // Cập nhật thời gian hoạt động nếu chưa hết hạn
            session(['lastActivityTime' => $currentTime]);
        }
        
        return $next($request);
    }
}
