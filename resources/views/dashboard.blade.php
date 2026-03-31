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
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            padding: 0.75rem 1rem; 
            border-radius: 0.5rem; 
            color: #64748b; 
            margin-bottom: 0.25rem; 
            text-decoration: none;
            white-space: nowrap; 
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
            <a href="/khach-hang" class="sidebar-item">
                <i class="fa-solid fa-users w-5 mr-3"></i>Quản lý khách hàng
            </a>
            <a href="/dien-ke" class="sidebar-item">
                <i class="fa-solid fa-gauge w-5 mr-3"></i>Quản lý điện kế
            </a>
            <a href="/hoa-don" class="sidebar-item">
                <i class="fa-solid fa-file-invoice-dollar w-5 mr-3"></i>Quản lý hóa đơn
            </a>
            <a href="/lich-su-gia" class="sidebar-item">
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

        <div class="w-full mx-auto">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Xin chào, Quản trị viên!</h2>
            <p class="text-slate-500 text-sm mb-8">Chào mừng bạn đến với hệ thống quản lý điện. Dưới đây là tổng quan tình hình hoạt động.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Tổng khách hàng</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ number_format($tongKhachHang ?? 1245) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Điện kế hoạt động</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ number_format($dienKeHoatDong ?? 1208) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-money-check-dollar"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Doanh thu tháng này</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ number_format($doanhThuThang ?? 145500000, 0, ',', '.') }} đ</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center flex flex-col justify-center">
                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-2">Bắt đầu phiên làm việc</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Bạn có thể quản lý danh sách khách hàng, cấp phát điện kế mới hoặc bắt đầu tính tiền điện.</p>
                    <div class="flex justify-center gap-4">
                        <a href="/khach-hang" class="px-6 py-2.5 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 transition shadow-sm shadow-blue-200">
                            <i class="fa-solid fa-plus mr-2"></i>Thêm Khách hàng
                        </a>
                        <a href="/dien-ke" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-50 transition">
                            Chuyển đến Điện kế
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Lịch sử thay đổi giá (Gần đây)</h3>
                        <a href="/lich-su-gia" class="text-blue-600 text-xs font-bold hover:underline">Xem tất cả</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-3 py-2">Bậc</th>
                                    <th class="px-3 py-2 text-right">Đơn giá</th>
                                    <th class="px-3 py-2 text-center">Ngày áp dụng</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @if(isset($lichSuGia) && $lichSuGia->count() > 0)
                                    @foreach($lichSuGia->take(5) as $gia)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-3 py-2 font-bold text-slate-700">{{ $gia->bacDien->tenbac }}</td>
                                        <td class="px-3 py-2 text-right font-bold text-blue-600">{{ number_format($gia->dongia) }} đ</td>
                                        <td class="px-3 py-2 text-center text-slate-400">{{ date('d/m/Y', strtotime($gia->ngayapdung)) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="px-3 py-2 font-bold text-slate-700">Bậc 1</td>
                                        <td class="px-3 py-2 text-right font-bold text-blue-600">1.806 đ</td>
                                        <td class="px-3 py-2 text-center text-slate-400">01/01/2026</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-bold text-slate-700">Bậc 2</td>
                                        <td class="px-3 py-2 text-right font-bold text-blue-600">1.866 đ</td>
                                        <td class="px-3 py-2 text-center text-slate-400">01/01/2026</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>