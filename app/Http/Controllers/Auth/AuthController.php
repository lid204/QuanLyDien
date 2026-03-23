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
        
        // 1. Kiểm tra rỗng (Cách truyền thống, nếu lỗi nó tự đá về trang cũ)
        $request->validate([
            'account' => 'required',
            'password' => 'required',
        ], [
            'account.required' => 'Vui lòng nhập Mã nhân viên hoặc Mã khách hàng.',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        // 2. Tìm trong bảng NhanVien
        $user = NhanVien::where('manv', $request->account)->first();
        $role = 'nhanvien';

        // 3. Nếu không có NhanVien, tìm trong bảng KhachHang
        if (!$user) {
            $user = KhachHang::where('makh', $request->account)->first();
            $role = 'khachhang';
        }

        // 4. Kiểm tra Mật khẩu & Lưu Session
        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user);
            Session::put('role', $role);
            Session::save();

            // Chuyển hướng trực tiếp luôn, không dùng JSON nữa
            if ($role == 'nhanvien') {
                return redirect('/dashboard');
            } else {
                return redirect('/tra-cuu');
            }
        }

        // 5. Báo lỗi nếu sai tài khoản/mật khẩu
        return back()->withErrors([
            'login_error' => 'Mã định danh hoặc mật khẩu không đúng!'
        ])->withInput($request->only('account')); // Giữ lại tên đăng nhập để khách không phải gõ lại
    }

    public function logout(Request $request) {
        Session::flush();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect()->route('login');
    }
}