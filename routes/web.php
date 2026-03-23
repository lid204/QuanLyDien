<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KhachHangController;
use App\Http\Middleware\CheckAdmin; // Đừng quên import Middleware này

// ==========================================
// 1. GIAO DIỆN CÔNG KHAI (PUBLIC)
// ==========================================
// Định nghĩa trang đăng nhập ở địa chỉ /login
Route::get('/login', function () { return view('auth.login'); })->name('login');

// Nếu người dùng vào trang chủ (/) thì tự động chuyển hướng sang /login
Route::get('/', function () { return redirect()->route('login'); });
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route hiển thị trang đăng ký và xử lý đăng ký [cite: 55]
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ==========================================
// 2. KHU VỰC DÀNH CHO NHÂN VIÊN (DÙNG MIDDLEWARE BẢO VỆ) 
// ==========================================
Route::middleware([CheckAdmin::class])->group(function () {
    
    // Trang chủ quản trị (Dashboard)
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // QUẢN LÝ KHÁCH HÀNG (CRUD) [cite: 8, 50]
    Route::get('/khach-hang', [KhachHangController::class, 'index'])->name('khach-hang.index');
    Route::post('/khach-hang/store', [KhachHangController::class, 'store']);
    Route::post('/khach-hang/update', [KhachHangController::class, 'update']);
    Route::post('/khach-hang/delete', [KhachHangController::class, 'destroy']);

    // QUẢN LÝ ĐIỆN KẾ (Sắp tới sẽ làm) [cite: 9, 71]
    // Route::get('/dien-ke', [DienKeController::class, 'index']);

    // QUẢN LÝ HÓA ĐƠN & TÍNH TIỀN [cite: 11, 95]
    // Route::post('/hoa-don/tinh-tien', [HoaDonController::class, 'tinhTienDien']);
});

// ==========================================
// 3. KHU VỰC DÀNH CHO KHÁCH HÀNG [cite: 77]
// ==========================================
Route::get('/tra-cuu', function () {
    return "Chào mừng khách hàng! Đây là trang tra cứu hóa đơn.";
})->name('tra-cuu');