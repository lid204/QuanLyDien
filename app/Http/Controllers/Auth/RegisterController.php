<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Kiểm tra dữ liệu
        $validator = Validator::make($request->all(), [
            'makh' => 'required|size:13|unique:khach_hang,makh',
            'dt' => 'required|numeric|unique:khach_hang,dt',
            'cmnd' => 'required|numeric|unique:khach_hang,cmnd',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        try {
            DB::table('khach_hang')->insert([
                'makh' => $request->makh,
                'tenkh' => $request->tenkh,
                'diachi' => $request->diachi,
                'dt' => $request->dt,
                'cmnd' => $request->cmnd,
                'password' => Hash::make($request->password),
                'created_at' => now()
            ]);

            return response()->json(['message' => 'Đăng ký thành công! Hãy đăng nhập.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}