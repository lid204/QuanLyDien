<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KhachHangController;
use App\Http\Middleware\CheckAdmin;

// --- Giao diện Public ---
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/', function () { return redirect()->route('login'); });
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// --- Khu vực dành cho Nhân viên (Bảo vệ bởi Middleware) ---
Route::middleware([CheckAdmin::class])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/khach-hang', [KhachHangController::class, 'index'])->name('khach-hang.index');
    Route::post('/khach-hang/store', [KhachHangController::class, 'store']);
    Route::post('/khach-hang/update', [KhachHangController::class, 'update']);
    Route::post('/khach-hang/delete', [KhachHangController::class, 'destroy']);
});

// --- Khu vực dành cho Khách hàng ---
Route::get('/tra-cuu', function () {
    return "Chào mừng khách hàng! Đây là trang tra cứu hóa đơn của bạn.";
})->name('tra-cuu');