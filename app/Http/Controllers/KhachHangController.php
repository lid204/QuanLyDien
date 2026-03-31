<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\KhachHang;
use App\Models\DienKe;

class KhachHangController extends Controller
{
    // 1. Hiển thị danh sách khách hàng
    public function index() {
        if(!Session::has('user')) return redirect()->route('login');
        $danhSachKH = KhachHang::with(['dienKes']) // Sửa lại 'dienKes' theo file Model KhachHang của bạn
            ->orderBy('created_at', 'desc')
            ->get();

        return view('khachhang', compact('danhSachKH'));
    }

    // 2. Thêm mới khách hàng & Tự động cấp điện kế
    public function store(Request $request) {
        // Đã bỏ makh và password. Bổ sung email
        $request->validate([
            'tenkh' => 'required|max:50',
            'email' => 'nullable|email|unique:khach_hang,email',
            'diachi' => 'required|max:100',
            'dt' => 'required|numeric|digits_between:10,12|unique:khach_hang,dt',
            'cmnd' => 'required|numeric|digits:9|unique:khach_hang,cmnd', 
        ], [
            'tenkh.required' => 'Vui lòng nhập Họ và tên.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'diachi.required' => 'Vui lòng nhập Địa chỉ.',
            'dt.required' => 'Vui lòng nhập Số điện thoại.',
            'dt.numeric' => 'Số điện thoại chỉ được nhập số.', 
            'dt.digits_between' => 'Số điện thoại phải từ 10 đến 12 số.',
            'dt.unique' => 'Số điện thoại này đã được đăng ký.',
            'cmnd.required' => 'Vui lòng nhập Số CMND.',
            'cmnd.numeric' => 'Số CMND chỉ được nhập số.',
            'cmnd.digits' => 'CMND phải có đúng 9 chữ số.',
            'cmnd.unique' => 'Số CMND này đã tồn tại trong hệ thống.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Thuật toán sinh mã 13 ký tự: K + 10 số thời gian + 2 số ngẫu nhiên
                do {
                    $newMakh = 'K' . time() . rand(10, 99);
                } while (KhachHang::where('makh', $newMakh)->exists());

                $kh = KhachHang::create([
                    'makh' => $newMakh,
                    'tenkh' => $request->tenkh,
                    'email' => $request->email,
                    'diachi' => $request->diachi,
                    'dt' => $request->dt,
                    'cmnd' => $request->cmnd,
                    'password' => Hash::make('123456') // Mặc định pass 123456
                ]);

                DienKe::create([
                    'madk' => 'DK' . rand(100000, 999999), 
                    'makh' => $kh->makh,
                    'diachi_lapdat' => $request->diachi,
                    'ngaysx' => now(),
                    'ngaylap' => now(),
                    'trangthai' => 1 
                ]);
            });

            return redirect()->back()->with('success', 'Đã thêm khách hàng và tự động cấp điện kế thành công!'); 

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    // 3. Cập nhật thông tin khách hàng 
    public function update(Request $request) {
        $kh = KhachHang::find($request->update_makh ?? $request->makh);
        
        if (!$kh) {
            return redirect()->back()->with('error', 'Không tìm thấy khách hàng.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'tenkh' => 'required|max:50',
            'email' => 'nullable|email|unique:khach_hang,email,' . $kh->makh . ',makh',
            'diachi' => 'required|max:100',
            'dt' => 'required|numeric|digits_between:10,12|unique:khach_hang,dt,' . $kh->makh . ',makh',
            'cmnd' => 'required|numeric|digits:9|unique:khach_hang,cmnd,' . $kh->makh . ',makh',
        ], [
            'tenkh.required' => 'Vui lòng nhập Họ và tên.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã thuộc về người khác.',
            'diachi.required' => 'Vui lòng nhập Địa chỉ.',
            'dt.required' => 'Vui lòng nhập Số điện thoại.',
            'dt.numeric' => 'Số điện thoại chỉ được nhập số.', 
            'dt.digits_between' => 'Số điện thoại phải từ 10 đến 12 số.',
            'dt.unique' => 'Số điện thoại này đã thuộc về người khác.',
            'cmnd.required' => 'Vui lòng nhập Số CMND.',
            'cmnd.numeric' => 'Số CMND chỉ được nhập số.',
            'cmnd.digits' => 'CMND phải có đúng 9 chữ số.',
            'cmnd.unique' => 'Số CMND này đã thuộc về người khác.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('is_updating', true); 
        }

        $data = $request->only(['tenkh', 'email', 'diachi', 'dt', 'cmnd']);
        
        if ($request->filled('password')) { 
            $data['password'] = Hash::make($request->password); 
        }

        $kh->update($data);
        return redirect()->back()->with('success', 'Cập nhật thông tin khách hàng thành công!');
    }

    // 4. Xóa khách hàng
    public function destroy(Request $request) {
        $kh = KhachHang::find($request->makh);

        if (!$kh) {
            return redirect()->back()->with('error', 'Không tìm thấy khách hàng.');
        }

        // Theo Model bạn mới gửi, hàm liên kết là dienKes()
        if ($kh->dienKes()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa! Khách hàng này đang sở hữu điện kế.');
        }

        try {
            $kh->delete();
            return redirect()->back()->with('success', 'Đã xóa hồ sơ khách hàng.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}