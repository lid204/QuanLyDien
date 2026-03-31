@extends('layouts.app')
@section('title', 'Quản lý khách hàng')

@section('content')
<div class="w-full mx-auto">
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

            <div class="grid grid-cols-7 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Mã KH (Tự cấp)</label>
                    <input type="text" name="makh" id="makh" class="input-field bg-slate-200 text-slate-500 font-bold" value="{{ old('makh') }}" 
                           placeholder="{{ session('is_updating') ? '' : 'K...' }}" readonly>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Họ tên *</label>
                    <input type="text" name="tenkh" id="tenkh" class="input-field" value="{{ old('tenkh') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Email</label>
                    <input type="email" name="email" id="email" class="input-field" value="{{ old('email') }}" placeholder="kh@gmail.com">
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
                           placeholder="{{ session('is_updating') ? 'Trống = ko đổi' : '123456' }}">
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

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap" id="customerTable">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600 font-bold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-center">Mã KH</th>
                    <th class="px-6 py-4 text-left">Tên khách hàng</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Địa chỉ</th>
                    <th class="px-6 py-4 text-center">SĐT</th>
                    <th class="px-6 py-4 text-center">CMND</th>
                    <th class="px-6 py-4 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @foreach($danhSachKH as $kh)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-blue-600 text-center text-base">{{ $kh->makh }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800 text-left">{{ $kh->tenkh }}</td>
                    <td class="px-6 py-4 text-slate-700 text-left">{{ $kh->email }}</td>
                    <td class="px-6 py-4 text-slate-700 text-left truncate max-w-[200px]" title="{{ $kh->diachi }}">{{ $kh->diachi }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700 text-center">{{ $kh->dt }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700 text-center">{{ $kh->cmnd }}</td>
                    <td class="px-6 py-4 text-center space-x-2 text-lg">
                        <button onclick="fillToEdit('{{ $kh->makh }}', '{{ addslashes($kh->tenkh) }}', '{{ addslashes($kh->email) }}', '{{ addslashes($kh->diachi) }}', '{{ $kh->dt }}', '{{ $kh->cmnd }}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition" title="Sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        
                        <form action="/khach-hang/delete" method="POST" class="inline" onsubmit="return confirm('Xóa hộ dân này?')">
                            @csrf
                            <input type="hidden" name="makh" value="{{ $kh->makh }}">
                            <button type="submit" class="text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function handleSearch() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#customerTable tbody tr");
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function fillToEdit(makh, tenkh, email, diachi, dt, cmnd) {
    const form = document.getElementById('customerForm');
    form.action = '/khach-hang/update'; 
    
    document.getElementById('update_makh').value = makh;
    
    document.getElementById('makh').value = makh;
    document.getElementById('tenkh').value = tenkh;
    document.getElementById('email').value = email; 
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
@endsection