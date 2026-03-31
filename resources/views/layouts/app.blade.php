<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Hệ thống quản lý điện</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .input-field { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.6rem 0.75rem; font-size: 0.875rem; width: 100%; outline: none; background-color: #f8fafc; }
        .sidebar-item { display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; color: #64748b; margin-bottom: 0.25rem; text-decoration: none; white-space: nowrap; transition: 0.2s; }
        /* Tự động đổi màu khi có class active */
        .sidebar-item.active { background-color: #eff6ff; color: #2563eb; font-weight: 600; }
        .sidebar-item:hover:not(.active) { background-color: #f1f5f9; color: #1e293b; }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 500; }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 bg-white border-r border-slate-200 p-6 fixed h-full shadow-sm">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white"><i class="fa-solid fa-bolt text-xl"></i></div>
            <h1 class="text-sm font-bold text-slate-800 leading-tight">Hệ thống quản lý điện</h1>
        </div>
        <nav>
            <a href="/dashboard" class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-home w-5 mr-3"></i>Trang chủ
            </a>
            <a href="/khach-hang" class="sidebar-item {{ request()->is('khach-hang*') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-5 mr-3"></i>Quản lý khách hàng
            </a>
            <a href="/dien-ke" class="sidebar-item {{ request()->is('dien-ke*') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge w-5 mr-3"></i>Quản lý điện kế
            </a>
            <a href="/hoa-don" class="sidebar-item {{ request()->is('hoa-don*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 mr-3"></i>Quản lý tính tiền điện
            </a>
<a href="/lich-su-gia" class="sidebar-item {{ request()->is('lich-su-gia*') ? 'active' : '' }}">
    <i class="fa-solid fa-clock-rotate-left w-5 mr-3"></i>Lịch sử giá điện
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

        @yield('content')
        
    </main>

    @yield('scripts')

</body>
</html>