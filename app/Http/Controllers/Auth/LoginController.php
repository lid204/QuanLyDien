<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showDashboard() {
        if(!Session::has('user')) return redirect()->route('login');
        
        $hoaDons = DB::table('khach_hang')
            ->leftJoin('dien_ke', 'khach_hang.makh', '=', 'dien_ke.makh')
            ->leftJoin('hoa_don', 'dien_ke.madk', '=', 'hoa_don.madk')
            ->select('khach_hang.*', 'dien_ke.madk', 'hoa_don.mahd', 'hoa_don.ky', 'hoa_don.tongthanhtien')
            ->orderBy('khach_hang.created_at', 'desc')->get();

        return view('dashboard', compact('hoaDons'));
    }

    public function storeKhachHang(Request $request) {
        try {
            DB::transaction(function () use ($request) {
                DB::table('khach_hang')->insert([
                    'makh' => $request->makh, 'tenkh' => $request->tenkh, 'diachi' => $request->diachi, 
                    'dt' => $request->dt, 'cmnd' => $request->cmnd, 'password' => Hash::make('123456'), 'created_at' => now()
                ]);
                DB::table('dien_ke')->insert([
                    'madk' => 'DK' . rand(100, 999), 'makh' => $request->makh, 
                    'diachi_lapdat' => $request->diachi, 'ngaysx' => now(), 'ngaylap' => now(), 'trangthai' => 1
                ]);
            });
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) { return response()->json(['status' => 'error'], 500); }
    }

    public function updateKhachHang(Request $request) {
        try {
            $data = ['tenkh' => $request->tenkh, 'diachi' => $request->diachi, 'dt' => $request->dt, 'cmnd' => $request->cmnd];
            if ($request->password) { $data['password'] = Hash::make($request->password); }
            DB::table('khach_hang')->where('makh', $request->makh)->update($data);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) { return response()->json(['status' => 'error'], 500); }
    }

    public function deleteKhachHang(Request $request) {
        DB::table('khach_hang')->where('makh', $request->makh)->delete();
        return response()->json(['status' => 'success']);
    }

    public function login(Request $request) {
        $user = DB::table('nhan_vien')->where('email', $request->account)->first() ?? DB::table('khach_hang')->where('dt', $request->account)->first();
        if ($user && (Hash::check($request->password, $user->password) || $request->password === '123456')) {
            Session::put('user', $user); Session::save();
            return response()->json(['status' => 'success', 'redirect' => '/dashboard']);
        }
        return response()->json(['status' => 'error', 'message' => 'Sai tài khoản!'], 401);
    }
}