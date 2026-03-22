<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <p class="text-sm text-slate-400 text-center mb-8">Hệ thống quản lý điện</p>

        <form onsubmit="handleLogin(event)">
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                <input type="text" id="account" class="input-box" placeholder="admin@dienluc.vn" required>
            </div>
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu</label>
                <input type="password" id="password" class="input-box" placeholder="••••••••" required>
            </div>

            <button type="submit" id="btnLogin" class="btn-login mb-6">
                Đăng Nhập
            </button>
        </form>

        <div class="text-center text-sm text-slate-500">
            Chưa có tài khoản? <a href="#" class="text-blue-600 font-bold hover:underline">Đăng ký ngay</a>
        </div>
    </div>

    <script>
    async function handleLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('btnLogin');
        btn.disabled = true; btn.innerText = 'Đang xử lý...';

        const payload = {
            account: document.getElementById('account').value,
            password: document.getElementById('password').value
        };

        try {
            const res = await fetch('/login', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (res.ok) window.location.href = data.redirect;
            else alert(data.message);
        } catch (err) { alert('Lỗi kết nối máy chủ!'); }
        finally { btn.disabled = false; btn.innerText = 'Đăng Nhập'; }
    }
    </script>
</body>
</html>