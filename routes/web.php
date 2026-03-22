<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;

// Auth & Logout
Route::get('/', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', function () {
    Session::flush();
    return redirect()->route('login');
})->name('logout');

// Dashboard & CRUD
Route::get('/dashboard', [LoginController::class, 'showDashboard'])->name('dashboard');
Route::post('/khach-hang/store', [LoginController::class, 'storeKhachHang']);
Route::post('/khach-hang/update', [LoginController::class, 'updateKhachHang']);
Route::post('/khach-hang/delete', [LoginController::class, 'deleteKhachHang']);
Route::post('/hoa-don/tinh-tien', [LoginController::class, 'tinhTienDien']);