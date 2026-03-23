<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Quản lý điện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        /* Cố định Sidebar item để không bao giờ xuống hàng */
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            padding: 0.75rem 1rem; 
            border-radius: 0.5rem; 
            color: #64748b; 
            margin-bottom: 0.25rem; 
            text-decoration: none;
            white-space: nowrap; /* Giữ chữ trên 1 dòng */
            transition: 0.2s;
        }
        .sidebar-item.active { background-color: #eff6ff; color: #2563eb; font-weight: 600; }
        .sidebar-item:hover:not(.active) { background-color: #f1f5f9; color: #1e293b; }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 bg-white border-r border-slate-200 p-6 fixed h-full shadow-sm">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                <i class="fa-solid fa-bolt text-xl"></i>
            </div>
            <h1 class="text-sm font-bold text-slate-800 leading-tight">Hệ thống quản lý điện</h1>
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="sidebar-item active">
                <i class="fa-solid fa-home w-5 mr-3"></i>Trang chủ
            </a>
            <a href="{{ route('khach-hang.index') }}" class="sidebar-item">
                <i class="fa-solid fa-users w-5 mr-3"></i>Quản lý khách hàng
            </a>
            <a href="#" class="sidebar-item">
                <i class="fa-solid fa-file-invoice-dollar w-5 mr-3"></i>Quản lý hóa đơn
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="flex justify-between items-center mb-8 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-slate-400 text-xs italic font-medium">Công ty Điện lực - Quản trị viên</div>
            <a href="{{ route('logout') }}" class="flex items-center text-slate-600 hover:text-red-500 font-bold text-xs transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Đăng xuất
            </a>
        </header>

        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Xin chào, Quản trị viên!</h2>
            <p class="text-slate-500 text-sm">Chào mừng bạn đến với hệ thống quản lý điện. Vui lòng chọn chức năng ở menu bên trái.</p>
            
            </div>
    </main>
</body>
</html>