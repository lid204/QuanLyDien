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
    public function register(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào 
        $request->validate([
            'makh' => 'required|size:13|unique:khach_hang,makh',
            'tenkh' => 'required|max:50',
            'diachi' => 'required|max:100',
            'dt' => 'required|digits_between:10,12|unique:khach_hang,dt',
            'cmnd' => 'required|digits:9|unique:khach_hang,cmnd', 
            'password' => 'required|min:6|confirmed' 
        ], [
            'makh.required' => 'Vui lòng nhập Mã khách hàng.',
            'makh.size' => 'Mã khách hàng phải đúng 13 ký tự.',
            'makh.unique' => 'Mã khách hàng này đã tồn tại.',
            
            'tenkh.required' => 'Vui lòng nhập Họ và tên.',
            
            'diachi.required' => 'Vui lòng nhập Địa chỉ.',
            
            'dt.required' => 'Vui lòng nhập Số điện thoại.',
            'dt.digits_between' => 'Số điện thoại phải từ 10 đến 12 số.',
            'dt.unique' => 'Số điện thoại này đã được đăng ký.',
            
            'cmnd.required' => 'Vui lòng nhập Số CMND.',
            'cmnd.digits' => 'CMND phải có đúng 9 chữ số.',
            'cmnd.unique' => 'Số CMND này đã tồn tại trong hệ thống.',
            
            'password.required' => 'Vui lòng nhập Mật khẩu.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.'
        ]);

        try {
            // 2. Dùng Transaction: Đăng ký khách hàng phải đi kèm cấp Điện kế
            DB::transaction(function () use ($request) {
                // Tạo hồ sơ khách hàng
                $kh = KhachHang::create([
                    'makh' => $request->makh,
                    'tenkh' => $request->tenkh,
                    'diachi' => $request->diachi,
                    'dt' => $request->dt,
                    'cmnd' => $request->cmnd,
                    'password' => Hash::make($request->password),
                ]);

                // Tự động cấp 01 điện kế cho nhà của khách hàng
                DienKe::create([
                    'madk' => 'DK' . rand(100000, 999999), 
                    'makh' => $kh->makh,
                    'diachi_lapdat' => $request->diachi,
                    'ngaysx' => now(),
                    'ngaylap' => now(),
                    'trangthai' => 1 
                ]);
            });

            // Nếu đăng ký thành công, tự động chuyển về trang Đăng nhập
            return redirect('/login')->with('success', 'Đăng ký thành công! Hãy đăng nhập.');

        } catch (\Exception $e) {
            // Nếu có lỗi hệ thống, báo lỗi và giữ lại dữ liệu khách vừa nhập
            return back()->withErrors(['register_error' => 'Lỗi hệ thống: ' . $e->getMessage()])->withInput();
        }
    }
}