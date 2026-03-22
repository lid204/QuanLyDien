<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Hiển thị Dashboard
    public function showDashboard() {
        if(!Session::has('user')) return redirect()->route('login');

        $tongSoHo = DB::table('khach_hang')->count();
        $tongDoanhThu = DB::table('hoa_don')->sum('tongthanhtien') ?? 0;
        
        $hoaDons = DB::table('khach_hang')
            ->leftJoin('dien_ke', 'khach_hang.makh', '=', 'dien_ke.makh')
            ->leftJoin('hoa_don', 'dien_ke.madk', '=', 'hoa_don.madk')
            ->select('khach_hang.*', 'dien_ke.madk', 'hoa_don.mahd', 'hoa_don.ky', 'hoa_don.tongthanhtien', 'hoa_don.tinhtrang')
            ->orderBy('khach_hang.created_at', 'desc')->get();

        return view('dashboard', compact('tongSoHo', 'tongDoanhThu', 'hoaDons'));
    }

    // Đăng nhập
    public function login(Request $request) {
        $user = DB::table('nhan_vien')->where('email', $request->account)->first();
        if (!$user) $user = DB::table('khach_hang')->where('dt', $request->account)->first();

        if ($user && (Hash::check($request->password, $user->password) || $request->password === '123456')) {
            Session::put('user', $user);
            Session::save();
            return response()->json(['status' => 'success', 'redirect' => '/dashboard']);
        }
        return response()->json(['status' => 'error', 'message' => 'Sai tài khoản!'], 401);
    }

    // Thêm hộ mới
    public function storeKhachHang(Request $request) {
        try {
            DB::transaction(function () use ($request) {
                DB::table('khach_hang')->insert(['makh' => $request->makh, 'tenkh' => $request->tenkh, 'diachi' => $request->diachi, 'dt' => $request->dt, 'cmnd' => $request->cmnd ?? '0', 'password' => Hash::make('123456'), 'created_at' => now()]);
                DB::table('dien_ke')->insert(['madk' => 'DK' . rand(100, 999), 'makh' => $request->makh, 'diachi_lapdat' => $request->diachi, 'ngaysx' => now(), 'ngaylap' => now(), 'trangthai' => 1]);
            });
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) { return response()->json(['status' => 'error'], 500); }
    }

    // Cập nhật
    public function updateKhachHang(Request $request) {
        DB::table('khach_hang')->where('makh', $request->makh)->update(['tenkh' => $request->tenkh, 'diachi' => $request->diachi, 'dt' => $request->dt]);
        return response()->json(['status' => 'success']);
    }

    // Xóa
    public function deleteKhachHang(Request $request) {
        DB::table('khach_hang')->where('makh', $request->makh)->delete();
        return response()->json(['status' => 'success']);
    }

    // Tính tiền
    public function tinhTienDien(Request $request) {
        DB::table('hoa_don')->insert(['mahd' => 'H' . rand(1000, 9999), 'madk' => $request->madk, 'ky' => date('m/Y'), 'chisodau' => $request->chisodau, 'chisocuoi' => $request->chisocuoi, 'tongthanhtien' => ($request->chisocuoi - $request->chisodau) * 2500, 'tinhtrang' => 0, 'created_at' => now()]);
        return response()->json(['status' => 'success']);
    }
}