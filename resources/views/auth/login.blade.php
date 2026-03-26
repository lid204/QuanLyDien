<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Hệ thống quản lý điện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f0f4ff; }
        .login-card { background: white; padding: 2.5rem; border-radius: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 420px; }
        .input-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.75rem 1rem; width: 100%; outline: none; transition: 0.2s; }
        .input-box:focus { border-color: #3b82f6; background-color: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .btn-login { background-color: #050514; color: white; width: 100%; padding: 0.875rem; border-radius: 0.75rem; font-weight: 700; transition: 0.2s; }
        .btn-login:hover { background-color: #1e1e2d; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="login-card">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <i class="fa-solid fa-bolt text-3xl"></i>
            </div>
        </div>

        <h2 class="text-xl font-bold text-center text-slate-800 mb-1">Đăng Nhập Hệ Thống</h2>
        <p class="text-sm text-slate-400 text-center mb-6">Hệ thống quản lý điện</p>

        @if($errors->any())
        <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-semibold text-center flex items-center justify-center gap-2 italic">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form action="/login" method="POST">
            @csrf 
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Mã nhân viên / Số điện thoại khách hàng</label>
                <input type="text" name="account" value="{{ old('account') }}" class="input-box" placeholder="Ví dụ: NV001 hoặc 09xxxxxxxx">
            </div>
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu</label>
                <input type="password" name="password" class="input-box" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login mb-6 shadow-lg shadow-slate-200 transition-all active:scale-95">
                Đăng Nhập
            </button>
        </form>

        <div class="text-center text-sm text-slate-500">
            Chưa có tài khoản? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Đăng ký ngay</a>
        </div>
    </div>

</body>
</html>