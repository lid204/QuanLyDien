<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\HoaDon;
use App\Models\CtHoaDon;
use App\Models\DienKe;
use App\Models\LichSuGia;

class HoaDonController extends Controller
{
    public function index() {
        if(!Session::has('user')) return redirect()->route('login');
        
        // Lấy danh sách hóa đơn
        $danhSachHD = HoaDon::with(['khachHang', 'dienKe'])->orderBy('thang_nam', 'desc')->get();
        
        // Lấy danh sách Điện kế đang hoạt động để đưa vào Form
        $danhSachDK = DienKe::with('khachHang')->where('trangthai', 1)->get();

        return view('hoadon', compact('danhSachHD', 'danhSachDK'));
    }

    public function store(Request $request) {
        // [BƯỚC 9 & 10] VÀ [BƯỚC 24 & 25]: Kiểm tra Mã ĐK tồn tại và Trạng thái phải là 1 (Đang hoạt động)
        $request->validate([
            'madk' => [
                'required',
                \Illuminate\Validation\Rule::exists('dien_ke', 'madk')->where(function ($query) {
                    return $query->where('trangthai', 1);
                }),
            ],
            'thang_nam' => 'required',
            'chisocuoi' => 'required|numeric|min:0'
        ], [
            'madk.required' => 'Vui lòng chọn điện kế.',
            'madk.exists' => 'Mã điện kế không tồn tại hoặc đã Ngừng dịch vụ!', // Thông báo chính xác như Bước 25
            'thang_nam.required' => 'Vui lòng chọn kỳ hóa đơn.',
            'chisocuoi.required' => 'Vui lòng nhập chỉ số cuối.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // [BƯỚC 11 & 12]: Controller tự truy vấn Chỉ số đầu kỳ trước từ DB (Bỏ qua số liệu Frontend gửi lên để bảo mật)
                $lastHD = HoaDon::where('madk', $request->madk)->orderBy('mahd', 'desc')->first();
                $chiSoDau = $lastHD ? $lastHD->chisocuoi : 0;

                // [BƯỚC 21, 22, 23 - NHÁNH FAIL]: Kiểm tra DNTT >= 0
                if ($request->chisocuoi < $chiSoDau) {
                    // Cố tình quăng ra Exception để DB::transaction rollback (Loại bỏ thao tác)
                    throw new \Exception('Chỉ số cuối mới (' . $request->chisocuoi . ') không được nhỏ hơn Chỉ số đầu kỳ trước (' . $chiSoDau . ')!');
                }

                // [BƯỚC 15]: Tính DNTT
                $dntt = $request->chisocuoi - $chiSoDau; 

                // [BƯỚC 13 & 14]: Truy vấn bảng BACDIEN & Lấy đơn giá (Thông qua LichSuGia)
                $giaDien = LichSuGia::with('bacDien')->where('trangthai', 1)->get()->sortBy('bacDien.mabac');
                
                $dienKe = DienKe::find($request->madk);
                $tongTien = 0;

                // Chuẩn bị ID Hóa đơn
                $mahd = 'HD' . time() . rand(10,99);

                // Insert trước phần Vỏ Hóa Đơn
                $hoaDon = HoaDon::create([
                    'mahd' => $mahd,
                    'makh' => $dienKe->makh,
                    'madk' => $dienKe->madk,
                    'manv' => Session::get('user')->manv ?? 'NV001',
                    'thang_nam' => $request->thang_nam,
                    'chisodau' => $chiSoDau, // Lấy biến an toàn truy vấn từ DB
                    'chisocuoi' => $request->chisocuoi,
                    'tongtien' => 0,
                    'trangthai_thanhtoan' => 0 
                ]);

                // [BƯỚC 16]: Tính tổng tiền (Chia 6 bậc)
                $soKyConLai = $dntt;
                foreach ($giaDien as $gia) {
                    if ($soKyConLai <= 0) break;

                    $bac = $gia->bacDien;
                    $soKyToiDaCuaBac = $bac->denky - $bac->tuky;
                    if ($bac->tuky == 0) $soKyToiDaCuaBac = $bac->denky; 
                    else $soKyToiDaCuaBac = $bac->denky - $bac->tuky + 1; 

                    $soKyTinhTien = min($soKyConLai, $soKyToiDaCuaBac);
                    $thanhTienBac = $soKyTinhTien * $gia->dongia;

                    // [BƯỚC 17]: Insert dữ liệu vào bảng CTHOADON
                    CtHoaDon::create([
                        'mahd' => $mahd,
                        'mabac' => $bac->mabac,
                        'soky' => $soKyTinhTien,
                        'dongia' => $gia->dongia,
                        'thanhtien' => $thanhTienBac
                    ]);

                    $tongTien += $thanhTienBac;
                    $soKyConLai -= $soKyTinhTien;
                }

                // [BƯỚC 18]: Xác nhận cập nhật thành công (Update tổng tiền + Thuế 10%)
                $tongTienSauThue = $tongTien + ($tongTien * 0.1);
                $hoaDon->update(['tongtien' => $tongTienSauThue]);
            });

            // [BƯỚC 19 & 20]: Trả kết quả về màn hình thành công
            return redirect()->back()->with('success', 'Lập hóa đơn và tính tiền điện thành công!');

        } catch (\Exception $e) {
            // Hứng lỗi nhánh Fail (Bước 22, 23)
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function show($mahd) {
    // Lấy thông tin hóa đơn cùng các quan hệ
    $hoaDon = HoaDon::with(['khachHang', 'dienKe', 'chiTietHoaDons.bacDien'])
        ->where('mahd', $mahd)
        ->firstOrFail();

    return view('hoadon_chitiet', compact('hoaDon'));
}
}