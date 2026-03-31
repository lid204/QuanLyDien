@extends('layouts.app')
@section('title', 'Quản lý điện kế')

@section('content')
<div class="w-full mx-auto">
    <h2 class="text-2xl font-bold text-slate-800">Quản lý điện kế</h2>
    <p class="text-slate-400 text-sm mb-6">Thêm mới, cập nhật và thay đổi trạng thái công tơ điện</p>

    @if(session('success'))
        <div class="alert bg-green-100 text-green-700 border border-green-200"><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</div>
    @endif
    @if(session('error') || $errors->any())
        <div class="alert bg-red-100 text-red-700 border border-red-200">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') ?? 'Vui lòng kiểm tra lại dữ liệu nhập vào.' }}
            <ul class="list-disc ml-5 mt-2 text-xs">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="relative mb-6">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" id="searchInput" onkeyup="handleSearch()" placeholder="Tìm kiếm nhanh..." class="w-full bg-white border border-slate-200 rounded-full py-3 pl-12 pr-6 text-sm outline-none focus:ring-2 focus:ring-blue-100 transition">
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
        <form id="dienKeForm" action="{{ session('is_updating') ? '/dien-ke/update' : '/dien-ke/store' }}" method="POST">
            @csrf
            <input type="hidden" name="update_madk" id="update_madk" value="{{ old('update_madk', session('is_updating') ? old('madk') : '') }}">

            <div class="grid grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Mã Điện Kế *</label>
                    <input type="text" name="madk" id="madk" class="input-field" value="{{ old('madk') }}" placeholder="VD: DK12345" {{ session('is_updating') ? 'readonly style=background-color:#f1f5f9' : '' }}>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Chủ sở hữu (Khách hàng) *</label>
                    <select name="makh" id="makh" class="input-field cursor-pointer">
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach($danhSachKH as $kh)
                            <option value="{{ $kh->makh }}" {{ old('makh') == $kh->makh ? 'selected' : '' }}>{{ $kh->makh }} - {{ $kh->tenkh }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Ngày lắp đặt *</label>
                    <input type="date" name="ngaylap" id="ngaylap" class="input-field" value="{{ old('ngaylap', date('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Địa chỉ lắp đặt *</label>
                    <input type="text" name="diachi_lapdat" id="diachi_lapdat" class="input-field" value="{{ old('diachi_lapdat') }}" placeholder="Số nhà, đường...">
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

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto text-sm">
        <table class="w-full text-left whitespace-nowrap" id="dkTable">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600 font-bold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-center">Mã ĐK</th>
                    <th class="px-6 py-4 text-left">Khách hàng</th>
                    <th class="px-6 py-4 text-left">Địa chỉ lắp đặt</th>
                    <th class="px-6 py-4 text-center">Ngày lắp</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @foreach($danhSachDK as $dk)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-blue-600 text-center text-base">{{ $dk->madk }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800 text-left">
                        {{ $dk->khachHang->tenkh ?? 'Không rõ' }}
                        <div class="text-xs text-slate-400 font-normal">{{ $dk->makh }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-700 text-left truncate max-w-[250px]">{{ $dk->diachi_lapdat }}</td>
                    <td class="px-6 py-4 font-medium text-slate-700 text-center">{{ \Carbon\Carbon::parse($dk->ngaylap)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($dk->trangthai == 1)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">Hoạt động</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold border border-red-200">Đã ngưng</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center space-x-2 text-lg">
                        <form action="/dien-ke/toggle-status" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="madk" value="{{ $dk->madk }}">
                            <button type="submit" class="text-slate-500 hover:text-orange-500 hover:bg-orange-50 p-2 rounded-lg transition" title="Đổi trạng thái">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </form>

                        <button onclick="fillToEdit('{{ $dk->madk }}', '{{ $dk->makh }}', '{{ addslashes($dk->diachi_lapdat) }}', '{{ $dk->ngaylap }}')" class="text-blue-500 hover:bg-blue-100 p-2 rounded-lg transition" title="Sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
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
    let rows = document.querySelectorAll("#dkTable tbody tr");
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function fillToEdit(madk, makh, diachi, ngaylap) {
    const form = document.getElementById('dienKeForm');
    form.action = '/dien-ke/update'; 
    
    document.getElementById('update_madk').value = madk;
    document.getElementById('madk').value = madk;
    document.getElementById('madk').readOnly = true;
    document.getElementById('madk').style.backgroundColor = "#f1f5f9";
    
    document.getElementById('makh').value = makh;
    document.getElementById('diachi_lapdat').value = diachi;
    
    document.getElementById('ngaylap').value = ngaylap.split(' ')[0]; 

    document.getElementById('actionButtons').innerHTML = `
        <button type="submit" class="bg-slate-900 text-white px-8 py-2.5 rounded-lg font-bold text-xs hover:bg-black transition shadow-sm">Cập nhật</button>
        <button type="button" onclick="window.location.reload()" class="bg-white border border-slate-200 text-slate-600 px-8 py-2.5 rounded-lg font-bold text-xs">Hủy</button>
    `;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endsection