<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\DienKe;
use App\Models\KhachHang;

class DienKeController extends Controller
{
    // [Khởi tạo màn hình] 
    // Bước 2 & 3: Truy vấn danh sách điện kế
    public function index() {
        if(!Session::has('user')) return redirect()->route('login');
        
        // Lấy danh sách điện kế kèm thông tin khách hàng
        $danhSachDK = DienKe::with('khachHang')->orderBy('ngaylap', 'desc')->get();
        // Lấy danh sách khách hàng để đổ vào thẻ <select> khi thêm/sửa
        $danhSachKH = KhachHang::orderBy('created_at', 'desc')->get();

        return view('dienke', compact('danhSachDK', 'danhSachKH'));
    }

    // [Trường hợp 1: Thêm mới Điện kế]
    public function store(Request $request) {
        // Bước 7 & 8: Kiểm tra trùng Mã ĐK & KH
        $request->validate([
            'madk' => 'required|unique:dien_ke,madk', 
            'makh' => 'required|exists:khach_hang,makh', 
            'diachi_lapdat' => 'required|max:150',
            'ngaylap' => 'required|date'
        ], [
            'madk.required' => 'Vui lòng nhập Mã điện kế.',
            'madk.unique' => 'Mã điện kế này đã tồn tại trong hệ thống.',
            'makh.required' => 'Vui lòng chọn Khách hàng.',
            'diachi_lapdat.required' => 'Vui lòng nhập địa chỉ lắp đặt.',
            'ngaylap.required' => 'Vui lòng chọn ngày lắp.'
        ]);

        try {
            // Bước 9 & 10: Thực thi lệnh Insert (Mặc định trạng thái: Hoạt động)
            DienKe::create([
                'madk' => $request->madk,
                'makh' => $request->makh,
                'diachi_lapdat' => $request->diachi_lapdat,
                'ngaylap' => $request->ngaylap,
                'trangthai' => 1 
            ]);

            // Bước 11 & 12: Trả về kết quả, thông báo "Thêm thành công"
            return redirect()->back()->with('success', 'Thêm điện kế mới thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // [Trường hợp 2: Sửa thông tin Điện kế] 
    // =========================================================================

    // Bước 14: Nhận Yêu cầu thông tin chi tiết
    public function edit($madk) {
        // Bước 15 & 16: Truy vấn dữ liệu cũ từ CSDL
        $dk = DienKe::find($madk);

        if (!$dk) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy điện kế']);
        }

        // Bước 17: Đưa dữ liệu lên form (Trả về định dạng JSON)
        return response()->json([
            'success' => true, 
            'data' => $dk
        ]);
    }

    // Bước 20: Nhận Dữ liệu cập nhật
    public function update(Request $request) {
        $dk = DienKe::find($request->update_madk);
        if (!$dk) return redirect()->back()->with('error', 'Không tìm thấy điện kế.');

        // Bước 21: Kiểm tra logic
        $request->validate([
            'makh' => 'required|exists:khach_hang,makh',
            'diachi_lapdat' => 'required|max:150',
            'ngaylap' => 'required|date'
        ], [
            'makh.required' => 'Vui lòng chọn Khách hàng.',
            'diachi_lapdat.required' => 'Vui lòng nhập địa chỉ lắp đặt.',
            'ngaylap.required' => 'Vui lòng chọn ngày lắp.'
        ]);

        try {
            // Bước 21 & 22 (tiếp): Thực thi lệnh Update
            $dk->update([
                'makh' => $request->makh,
                'diachi_lapdat' => $request->diachi_lapdat,
                'ngaylap' => $request->ngaylap
            ]);

            // Bước 23 & 24: Trả về kết quả, thông báo "Sửa thành công"
            return redirect()->back()->with('success', 'Sửa thông tin điện kế thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    // [Trường hợp 3: Cập nhật trạng thái Ngưng/Hoạt động]
    // Bước 27: Gửi yêu cầu đổi trạng thái
    public function toggleStatus(Request $request) {
        $dk = DienKe::find($request->madk);
        if (!$dk) return redirect()->back()->with('error', 'Không tìm thấy điện kế.');

        try {
            // Bước 28 & 29: Thực thi Update trạng thái trong bảng DIENKE
            $dk->update(['trangthai' => $dk->trangthai == 1 ? 0 : 1]);
            
            $statusName = $dk->trangthai == 1 ? 'Hoạt động' : 'Đã ngưng';
            
            // Bước 30 & 31: Trả về kết quả, thông báo thành công
            return redirect()->back()->with('success', 'Thay đổi trạng thái thành: ' . $statusName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
}