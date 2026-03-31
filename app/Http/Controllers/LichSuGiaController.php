<?php
namespace App\Http\Controllers;

use App\Models\LichSuGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LichSuGiaController extends Controller
{
    public function index() {
        if(!Session::has('user')) return redirect()->route('login');

        // BƯỚC 3: Tính toán mốc thời gian (Từ ngày hiện tại lùi về 2 năm)
        $tuNgay = Carbon::now()->subYears(2);

        // BƯỚC 4 & 5: Yêu cầu & Nhận danh sách từ CSDL
        // BƯỚC 6: Định dạng & Sắp xếp theo Ngày đổi DESC
        $lichSuGia = LichSuGia::with(['bacDien', 'nhanVien'])
            ->where('ngayapdung', '>=', $tuNgay)
            ->orderBy('ngayapdung', 'desc')
            ->get();

        // Kiểm tra có dữ liệu hay không (Luồng Alt trong sơ đồ)
        $hasData = $lichSuGia->isNotEmpty();

        return view('gia_dien', compact('lichSuGia', 'hasData'));
    }
}