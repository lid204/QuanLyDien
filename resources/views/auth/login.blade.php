<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập hệ thống</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">HỆ THỐNG QUẢN LÝ ĐIỆN</h2>
        <form onsubmit="handleLogin(event)">
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Tài khoản:</label>
                <input type="text" id="acc" class="w-full border p-2 rounded outline-none focus:border-blue-500" value="admin@dienluc.vn" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">Mật khẩu:</label>
                <input type="password" id="pw" class="w-full border p-2 rounded outline-none focus:border-blue-500" placeholder="******" required>
            </div>
            <button type="submit" id="btnLogin" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700 transition">ĐĂNG NHẬP</button>
        </form>
    </div>

    <script>
    async function handleLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('btnLogin');
        btn.innerText = 'Đang kết nối...';
        btn.disabled = true;

        try {
            const res = await fetch('/login', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify({ account: document.getElementById('acc').value, password: document.getElementById('pw').value })
            });
            const data = await res.json();
            if (res.ok) window.location.href = data.redirect;
            else alert(data.message);
        } catch (err) { alert('Lỗi kết nối server!'); }
        finally { btn.innerText = 'ĐĂNG NHẬP'; btn.disabled = false; }
    }
    </script>
</body>
</html>