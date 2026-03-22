<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ thống Quản lý Điện năng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f1f5f9; font-family: sans-serif; }
        .input-box { border: 1px solid #cbd5e1; padding: 10px; border-radius: 6px; font-size: 13px; width: 100%; outline: none; transition: 0.2s; }
        .input-box:focus { border-color: #2563eb; background: #fff; }
        label { display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase; }
    </style>
</head>
<body>
    <nav class="bg-[#1e293b] text-white px-8 py-3 flex justify-between items-center shadow-md">
        <h1 class="font-bold uppercase italic text-sm text-blue-400">Hệ thống Quản lý Điện</h1>
        <div class="flex items-center gap-4 text-xs">
            <span class="opacity-70">Chào, {{ Session::get('user')->tennv ?? 'Admin' }}</span>
            <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-600 px-4 py-1.5 rounded font-bold transition shadow-sm">Thoát</a>
        </div>
    </nav>

    <div class="p-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 text-center">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-500 text-center">
                <p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Doanh thu dự kiến</p>
                <h3 class="text-2xl font-bold text-blue-900">{{ number_format($tongDoanhThu) }} VNĐ</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-green-500 text-center">
                <p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Số hộ tiêu thụ</p>
                <h3 class="text-2xl font-bold text-green-700">{{ $tongSoHo }} Hộ</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-10">
            <h2 class="text-slate-700 font-bold text-sm uppercase mb-8 italic border-b pb-2">Đăng ký hộ dân mới</h2>
            <form onsubmit="handleSaveCustomer(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5">
                    <div class="space-y-4">
                        <div><label>Mã khách hàng:</label><input type="text" id="makh" class="input-box" placeholder="K07" required></div>
                        <div><label>Tên chủ hộ:</label><input type="text" id="tenkh" class="input-box" placeholder="Loc" required></div>
                        <div><label>Địa chỉ:</label><input type="text" id="diachi" class="input-box" placeholder="341 TPHCM" required></div>
                    </div>
                    <div class="space-y-4">
                        <div><label>Số điện thoại:</label><input type="text" id="dt" class="input-box" placeholder="123421124" required></div>
                        <div><label>Số CMND/CCCD:</label><input type="text" id="cmnd" class="input-box" placeholder="254341342412"></div>
                        <div><label>Mật khẩu mặc định:</label><input type="text" class="input-box bg-gray-50 text-gray-400" value="123456" disabled></div>
                    </div>
                </div>
                <div class="mt-8 flex justify-center"><button type="submit" id="btnSubmit" class="bg-blue-600 text-white px-12 py-3 rounded-lg font-bold uppercase text-xs">Thêm hộ dân</button></div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden text-sm">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold border-b">
                    <tr>
                        <th class="px-6 py-4">Mã KH</th>
                        <th class="px-6 py-4">Chủ hộ / HĐ</th>
                        <th class="px-6 py-4 text-right">Thành tiền</th>
                        <th class="px-6 py-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 italic">
                    @foreach($hoaDons as $hd)
                    <tr class="hover:bg-blue-50/40 transition-colors group">
                        <td class="px-6 py-4 font-bold text-blue-600 text-xs">{{ $hd->makh }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 uppercase not-italic">{{ $hd->tenkh }}</div>
                            <div class="text-[10px] text-slate-400 italic">{{ $hd->mahd ? 'HĐ: '.$hd->mahd.' - Kỳ '.$hd->ky : 'Chưa lập hóa đơn' }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-right">
                            @if($hd->mahd) {{ number_format($hd->tongthanhtien) }}đ 
                            @else <button onclick="promptTinhTien('{{ $hd->madk }}')" class="text-blue-500 hover:underline">Tính tiền ngay</button> @endif
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center items-center space-x-3">
                            <button onclick="alert('Địa chỉ: {{ $hd->diachi }}\nSĐT: {{ $hd->dt }}')" class="text-gray-400 hover:text-blue-500" title="Xem chi tiết"><i class="fa fa-eye"></i></button>
                            <button onclick="handleEdit('{{ $hd->makh }}', '{{ $hd->tenkh }}', '{{ $hd->diachi }}', '{{ $hd->dt }}')" class="text-gray-400 hover:text-amber-500" title="Sửa"><i class="fa fa-pen-to-square"></i></button>
                            <button onclick="handleDelete('{{ $hd->makh }}')" class="text-gray-400 hover:text-red-500" title="Xóa"><i class="fa fa-trash-can"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
    async function handleSaveCustomer(e) {
        e.preventDefault();
        const payload = { makh: document.getElementById('makh').value, tenkh: document.getElementById('tenkh').value, dt: document.getElementById('dt').value, diachi: document.getElementById('diachi').value };
        const res = await fetch('/khach-hang/store', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload) });
        if(res.ok) window.location.reload();
    }

    async function handleDelete(makh) {
        if(!confirm('Bạn có chắc chắn muốn xóa hộ dân này?')) return;
        const res = await fetch('/khach-hang/delete', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ makh }) });
        if(res.ok) window.location.reload();
    }

    function handleEdit(makh, tenkh, diachi, dt) {
        const newTen = prompt("Tên chủ hộ mới:", tenkh);
        const newDc = prompt("Địa chỉ mới:", diachi);
        const newDt = prompt("SĐT mới:", dt);
        if(!newTen) return;
        fetch('/khach-hang/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ makh, tenkh: newTen, diachi: newDc, dt: newDt }) })
        .then(res => res.ok && window.location.reload());
    }

    async function promptTinhTien(madk) {
        const dau = prompt("Chỉ số ĐẦU:"); const cuoi = prompt("Chỉ số CUỐI:");
        if(!dau || !cuoi) return;
        const res = await fetch('/hoa-don/tinh-tien', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ madk, chisodau: dau, chisocuoi: cuoi }) });
        if(res.ok) window.location.reload();
    }
    </script>
</body>
</html>