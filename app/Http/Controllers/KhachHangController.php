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
        $danhSachKH = KhachHang::with(['dienKe'])
            ->orderBy('created_at', 'desc')
            ->get();

        
        return view('khachhang', compact('danhSachKH'));
    }

    // 2. Thêm mới khách hàng & Tự động cấp điện kế
    public function store(Request $request) {
        $request->validate([
            'makh' => 'required|size:13|unique:khach_hang,makh',
            'tenkh' => 'required|max:50',
            'diachi' => 'required|max:100',
            'dt' => 'required|digits_between:10,12|unique:khach_hang,dt',
            'cmnd' => 'required|digits:9|unique:khach_hang,cmnd', 
            'password' => 'required|min:6' 
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
        ]);

        try {
            DB::transaction(function () use ($request) {
                $kh = KhachHang::create([
                    'makh' => $request->makh,
                    'tenkh' => $request->tenkh,
                    'diachi' => $request->diachi,
                    'dt' => $request->dt,
                    'cmnd' => $request->cmnd,
                    'password' => Hash::make('123456') 
                ]);

                DienKe::create([
                    'madk' => rand(10000000, 99999999), 
                    'makh' => $kh->makh,
                    'diachi_lapdat' => $request->diachi,
                    'ngaysx' => now(),
                    'ngaylap' => now(),
                    'trangthai' => 1 
                ]);
            });

            return redirect()->back()->with('success', 'Đã thêm khách hàng và cấp điện kế thành công!'); 

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    // 3. Cập nhật thông tin khách hàng 
    public function update(Request $request) {
    $kh = KhachHang::find($request->makh);
    
    if (!$kh) {
        return redirect()->back()->with('error', 'Không tìm thấy khách hàng.');
    }

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'tenkh' => 'required|max:50',
        'diachi' => 'required|max:100',
        // Đã thêm numeric để bắt lỗi khi nhập chữ
        'dt' => 'required|numeric|digits_between:10,12|unique:khach_hang,dt,' . $kh->makh . ',makh',
        'cmnd' => 'required|numeric|digits:9|unique:khach_hang,cmnd,' . $kh->makh . ',makh',
    ], [
        'tenkh.required' => 'Vui lòng nhập Họ và tên.',
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
        // Gửi flags is_updating để file Blade giữ nguyên trạng thái nút "Cập nhật"
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('is_updating', true); 
    }

    $data = $request->only(['tenkh', 'diachi', 'dt', 'cmnd']);
    
    // Chỉ mã hóa và cập nhật nếu có nhập mật khẩu mới
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

        if ($kh->dienKe()->exists()) {
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