<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use App\Models\KhachHang;
use App\Models\DienKe; 

class RegisterController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'dt' => 'required|numeric|digits_between:10,12|unique:khach_hang,dt', 
            'tenkh' => 'required|max:50',
            'diachi' => 'required|max:100',
            'email' => 'nullable|email|unique:khach_hang,email', 
            'password' => 'required|min:6|confirmed' 
        ], [
            'dt.required' => 'Số điện thoại là bắt buộc.',
            'dt.numeric' => 'Số điện thoại chỉ được nhập số.',
            'dt.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Thuật toán cấp mã đồng bộ với KhachHangController
                do {
                    $newMakh = 'K' . time() . rand(10, 99);
                } while (KhachHang::where('makh', $newMakh)->exists());

                $kh = KhachHang::create([
                    'makh' => $newMakh,
                    'tenkh' => $request->tenkh,
                    'email' => $request->email,
                    'diachi' => $request->diachi,
                    'dt' => $request->dt,
                    'password' => Hash::make($request->password),
                ]);

                // Tự động cấp Điện kế kèm theo
                DienKe::create([
                    'madk' => 'DK' . rand(100000, 999999), 
                    'makh' => $kh->makh,
                    'diachi_lapdat' => $request->diachi,
                    'ngaysx' => now(), 
                    'ngaylap' => now(), 
                    'trangthai' => 1 
                ]);
            });
            return redirect('/login')->with('success', 'Đăng ký thành công! Hãy dùng SĐT để đăng nhập.');
        } catch (\Exception $e) {
            return back()->withErrors(['register_error' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }
}