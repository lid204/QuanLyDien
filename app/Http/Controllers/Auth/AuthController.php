<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\KhachHang;
use App\Models\NhanVien; 

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate(['account' => 'required', 'password' => 'required']);

        // 1. Tìm trong bảng NhanVien bằng Mã NV
        $user = NhanVien::where('manv', $request->account)->first();
        $role = 'nhanvien';

        if (!$user) {
            // 2. Nếu không có NV, tìm trong bảng KhachHang bằng SĐT (cột dt)
            $user = KhachHang::where('dt', $request->account)->first();
            $role = 'khachhang';
        }

        // 3. Kiểm tra mật khẩu
        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user);
            Session::put('role', $role);
            Session::save();

            return ($role == 'nhanvien') ? redirect('/dashboard') : redirect('/tra-cuu');
        }

        return back()->withErrors(['login_error' => 'Thông tin đăng nhập không chính xác!'])->withInput();
    }

    public function logout(Request $request) {
        Session::flush();
        return redirect()->route('login');
    }
}