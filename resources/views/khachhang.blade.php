<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý khách hàng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .input-field { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.6rem 0.75rem; font-size: 0.875rem; width: 100%; outline: none; background-color: #f8fafc; }
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            padding: 0.75rem 1rem; 
            border-radius: 0.5rem; 
            color: #64748b; 
            margin-bottom: 0.25rem; 
            text-decoration: none;
            white-space: nowrap; /* Giữ chữ trên một hàng */
            transition: 0.2s;
        }
        .sidebar-item.active { background-color: #eff6ff; color: #2563eb; font-weight: 600; }
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
            <a href="{{ route('dashboard') }}" class="sidebar-item"><i class="fa-solid fa-home w-5 mr-3"></i>Trang chủ</a>
            <a href="{{ route('khach-hang.index') }}" class="sidebar-item active"><i class="fa-solid fa-users w-5 mr-3"></i>Quản lý khách hàng</a>
            <a href="#" class="sidebar-item"><i class="fa-solid fa-file-invoice-dollar w-5 mr-3"></i>Quản lý hóa đơn</a>
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
            <h2 class="text-2xl font-bold text-slate-800">Quản lý khách hàng</h2>
            <p class="text-slate-400 text-sm mb-6">Quản lý và tìm kiếm thông tin khách hàng</p>

            @if(session('success'))
                <div class="alert bg-green-100 text-green-700 border border-green-200">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert bg-red-100 text-red-700 border border-red-200">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    {{ session('error') ?? 'Vui lòng kiểm tra lại dữ liệu nhập vào.' }}
                    <ul class="list-disc ml-5 mt-2 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="relative mb-6">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" onkeyup="handleSearch()" placeholder="Tìm kiếm nhanh trên trang này..." class="w-full bg-white border border-slate-200 rounded-full py-3 pl-12 pr-6 text-sm outline-none focus:ring-2 focus:ring-blue-100 transition">
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
                <form id="customerForm" action="{{ session('is_updating') ? '/khach-hang/update' : '/khach-hang/store' }}" method="POST">
                    @csrf
                    <input type="hidden" name="update_makh" id="update_makh" value="{{ old('update_makh', session('is_updating') ? old('makh') : '') }}">

                    <div class="grid grid-cols-6 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Mã KH *</label>
                            <input type="text" name="makh" id="makh" class="input-field" value="{{ old('makh') }}" 
                                   {{ session('is_updating') ? 'readonly style=background-color:#f1f5f9' : '' }}>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Họ tên *</label>
                            <input type="text" name="tenkh" id="tenkh" class="input-field" value="{{ old('tenkh') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Địa chỉ *</label>
                            <input type="text" name="diachi" id="diachi" class="input-field" value="{{ old('diachi') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Điện thoại *</label>
                            <input type="text" name="dt" id="dt" class="input-field" value="{{ old('dt') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">CMND *</label>
                            <input type="text" name="cmnd" id="cmnd" class="input-field" value="{{ old('cmnd') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase" id="labelPass">
                                {{ session('is_updating') ? 'Mật khẩu mới' : 'Mật khẩu' }}
                            </label>
                            <input type="password" name="password" id="password" class="input-field" 
                                   placeholder="{{ session('is_updating') ? 'Để trống nếu không đổi' : '123456' }}">
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2" id="actionButtons">
                        @if(session('is_updating'))
                            <button type="submit" class="bg-slate-900 text-white px-8 py-2.5 rounded-lg font-bold text-xs hover:bg-black transition shadow-sm">Cập nhật</button>
                            <button type="button" onclick="window.location.reload()" class="bg-white border border-slate-200 text-slate-600 px-8 py-2.5 rounded-lg font-bold text-xs">Hủy</button>
                        @else
                            <button type="submit" class="bg-slate-900 text-white px-8 py-2.5 rounded-lg font-bold text-xs hover:bg-black transition shadow-sm">Thêm mới</button>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-sm">
                <table class="w-full text-left" id="customerTable">
                    <thead class="bg-slate-50 text-[11px] uppercase text-slate-400 font-bold border-b text-center">
                        <tr><th class="px-6 py-4">Mã KH</th><th class="px-6 py-4">Tên khách hàng</th><th class="px-6 py-4">Địa chỉ</th><th class="px-6 py-4">SĐT</th><th class="px-6 py-4">CMND</th><th class="px-6 py-4">Thao tác</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @foreach($danhSachKH as $kh)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-blue-600 text-center">{{ $kh->makh }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700 not-italic">{{ $kh->tenkh }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $kh->diachi }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $kh->dt }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $kh->cmnd }}</td>
                            <td class="px-6 py-4 text-center space-x-2 text-lg">
                                <button onclick="fillToEdit('{{ $kh->makh }}', '{{ addslashes($kh->tenkh) }}', '{{ addslashes($kh->diachi) }}', '{{ $kh->dt }}', '{{ $kh->cmnd }}')" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg border border-slate-100"><i class="fa-solid fa-pen-to-square"></i></button>
                                
                                <form action="/khach-hang/delete" method="POST" class="inline" onsubmit="return confirm('Xóa hộ dân này?')">
                                    @csrf
                                    <input type="hidden" name="makh" value="{{ $kh->makh }}">
                                    <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg border border-slate-100"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    function handleSearch() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#customerTable tbody tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }

    function fillToEdit(makh, tenkh, diachi, dt, cmnd) {
        const form = document.getElementById('customerForm');
        form.action = '/khach-hang/update'; 
        
        // Gán mã khách hàng vào input ẩn để đánh dấu trạng thái update
        document.getElementById('update_makh').value = makh;
        
        document.getElementById('makh').value = makh;
        document.getElementById('makh').readOnly = true;
        document.getElementById('makh').style.backgroundColor = "#f1f5f9";
        document.getElementById('tenkh').value = tenkh;
        document.getElementById('diachi').value = diachi;
        document.getElementById('dt').value = dt;
        document.getElementById('cmnd').value = cmnd;
        document.getElementById('labelPass').innerText = "Mật khẩu mới";
        document.getElementById('password').placeholder = "Để trống nếu không đổi";

        document.getElementById('actionButtons').innerHTML = `
            <button type="submit" class="bg-slate-900 text-white px-8 py-2.5 rounded-lg font-bold text-xs hover:bg-black transition shadow-sm">Cập nhật</button>
            <button type="button" onclick="window.location.reload()" class="bg-white border border-slate-200 text-slate-600 px-8 py-2.5 rounded-lg font-bold text-xs">Hủy</button>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
</body>
</html>