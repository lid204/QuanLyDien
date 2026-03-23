<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        
        if (!Session::has('user')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }
        if (Session::get('role') !== 'nhanvien') {
            return redirect('/tra-cuu')->with('error', 'Bạn không có quyền truy cập trang quản lý!');
        }

        return $next($request);
    }
}