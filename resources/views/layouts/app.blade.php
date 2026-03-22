<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ thống Quản lý Điện năng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 250px; min-height: 100vh; background: #f8f9fa; border-right: 1px solid #dee2e6; }
        .main-content { flex: 1; background: #ffffff; }
        .nav-link:hover { background: #e9ecef; }
        input, select { border: 1px solid #ced4da; padding: 4px 8px; font-size: 14px; }
    </style>
</head>
<body class="flex">
    <div class="sidebar p-4 flex flex-col">
        <div class="text-blue-800 font-bold text-lg mb-6 border-b pb-2">
            <i class="fa-solid fa-database"></i> SQL Manager
        </div>
        
        <nav class="space-y-2 flex-1 text-sm">
            <a href="/" class="nav-link block p-2 rounded text-gray-700"><i class="fa-solid fa-house w-6"></i> Trang chủ</a>
            @if(Session::has('user'))
                <div class="text-xs font-bold text-gray-400 uppercase mt-4 mb-2">Quản lý</div>
                <a href="#" class="nav-link block p-2 rounded text-gray-700"><i class="fa-solid fa-users w-6"></i> Khách hàng</a>
                <a href="#" class="nav-link block p-2 rounded text-gray-700"><i class="fa-solid fa-bolt w-6"></i> Điện kế</a>
                <a href="#" class="nav-link block p-2 rounded text-gray-700"><i class="fa-solid fa-file-invoice-dollar w-6"></i> Hóa đơn</a>
                <a href="#" class="nav-link block p-2 rounded text-gray-700"><i class="fa-solid fa-chart-line w-6"></i> Báo cáo</a>
            @endif
        </nav>

        <div class="border-t pt-4">
            @if(Session::has('user'))
                <div class="text-xs text-gray-500 mb-2 italic">User: {{ Session::get('user')->tennv ?? Session::get('user')->tenkh }}</div>
                <a href="/logout" class="text-red-600 text-sm hover:underline"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            @else
                <a href="/login" class="text-blue-600 text-sm hover:underline"><i class="fa-solid fa-user-lock"></i> Đăng nhập hệ thống</a>
            @endif
        </div>
    </div>

    <div class="main-content flex flex-col">
        <header class="h-12 border-b flex items-center px-6 justify-between bg-gray-50">
            <div class="text-sm text-gray-600">Server: 127.0.0.1 » Database: quan_ly_dien</div>
            <div class="flex space-x-4 text-xs">
                <span><i class="fa-solid fa-microchip"></i> PHP: 8.2</span>
                <span><i class="fa-solid fa-server"></i> MySQL: 8.0</span>
            </div>
        </header>
        
        <div class="p-6">
            @yield('content')
        </div>
    </div>
</body>
</html>