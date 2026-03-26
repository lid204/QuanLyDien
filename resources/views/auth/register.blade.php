<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Hệ thống quản lý điện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f0f4ff; }
        .login-card { background: white; padding: 2.5rem; border-radius: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        .input-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.75rem 1rem; width: 100%; outline: none; transition: 0.2s; }
        .input-box:focus { border-color: #3b82f6; background-color: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .btn-login { background-color: #050514; color: white; width: 100%; padding: 0.875rem; border-radius: 0.75rem; font-weight: 700; transition: 0.2s; }
        .btn-login:hover { background-color: #1e1e2d; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="login-card">
        <h2 class="text-xl font-bold text-center text-slate-800 mb-1">Đăng Ký Tài Khoản</h2>
        <p class="text-sm text-slate-400 text-center mb-8">Dành cho khách hàng mới</p>

        @if($errors->any())
        <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-semibold text-center flex items-center justify-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form action="/register" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Số điện thoại (*)</label>
                    <input type="text" name="dt" value="{{ old('dt') }}" class="input-box" placeholder="09xxxxxxxx" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Họ và tên</label>
                    <input type="text" name="tenkh" value="{{ old('tenkh') }}" class="input-box" placeholder="Nguyễn Văn A" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Địa chỉ nhà</label>
                <input type="text" name="diachi" value="{{ old('diachi') }}" class="input-box" placeholder="Số nhà, tên đường..." required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Email (Không bắt buộc)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-box" placeholder="khachhang@gmail.com">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu</label>
                    <input type="password" name="password" class="input-box" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Xác nhận MK</label>
                    <input type="password" name="password_confirmation" class="input-box" required>
                </div>
            </div>

            <button type="submit" class="btn-login mb-6">Đăng Ký Ngay</button>
        </form>

        <div class="text-center text-sm text-slate-500">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập</a>
        </div>
    </div>
</body>
</html>