@extends('layouts.app')
@section('title', 'Quản lý tính tiền điện')

@section('content')
<div class="w-full mx-auto">
    <h2 class="text-2xl font-bold text-slate-800">Quản lý hóa đơn tiền điện</h2>
    <p class="text-slate-400 text-sm mb-6">Lập hóa đơn mới và theo dõi tình trạng thanh toán</p>

    @if(session('success'))
        <div class="alert bg-green-100 text-green-700 border border-green-200 mb-4 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div class="alert bg-red-100 text-red-700 border border-red-200 mb-4 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') ?? 'Vui lòng kiểm tra lại dữ liệu nhập vào.' }}
            <ul class="list-disc ml-5 mt-2 text-xs">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-slate-700 mb-4 border-b pb-2">
            <i class="fa-solid fa-calculator text-blue-500 mr-2"></i>Nhập chỉ số điện tháng này
        </h3>
        <form action="/hoa-don/store" method="POST">
            @csrf
            <div class="grid grid-cols-5 gap-4 items-end">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Chọn Điện kế *</label>
                    <select name="madk" id="madk" class="input-field cursor-pointer w-full" onchange="autoFillChiSoDau(this)">
                        <option value="">-- Chọn điện kế --</option>
                        @foreach($danhSachDK as $dk)
                            @php
                                // FIX: Sắp xếp theo ID giảm dần để lấy hóa đơn mới nhất thực tế trong DB
                                $lastHD = \App\Models\HoaDon::where('madk', $dk->madk)
                                            ->orderBy('mahd', 'desc') 
                                            ->first();
                                $chiSoDau = $lastHD ? $lastHD->chisocuoi : 0;
                            @endphp
                            <option value="{{ $dk->madk }}" data-chisodau="{{ $chiSoDau }}">
                                {{ $dk->madk }} - {{ $dk->khachHang->tenkh ?? 'Khách hàng không tên' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Kỳ (Tháng/Năm) *</label>
                    <input type="month" name="thang_nam" class="input-field w-full" value="{{ date('Y-m') }}" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Chỉ số đầu *</label>
                    <input type="number" name="chisodau" id="chisodau" class="input-field bg-slate-100 w-full" value="0" readonly>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase text-blue-600">Chỉ số cuối mới *</label>
                    <input type="number" name="chisocuoi" id="chisocuoi" class="input-field border-blue-400 focus:ring-blue-200 w-full" required>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-sm">
                    <i class="fa-solid fa-file-invoice-dollar mr-2"></i>Tính tiền & Lập hóa đơn
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto text-sm">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600 font-bold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-center">Mã HĐ</th>
                    <th class="px-6 py-4">Khách hàng / Điện kế</th>
                    <th class="px-6 py-4 text-center">Kỳ HĐ</th>
                    <th class="px-6 py-4 text-center">CS Đầu - Cuối</th>
                    <th class="px-6 py-4 text-right">Tổng tiền (VNĐ)</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($danhSachHD as $hd)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-blue-600 text-center">{{ $hd->mahd }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">
                        {{ $hd->khachHang->tenkh ?? 'N/A' }}
                        <div class="text-xs text-slate-400 font-normal">Mã ĐK: {{ $hd->madk }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-700 text-center font-bold">
                        {{ date('m/Y', strtotime($hd->thang_nam)) }}
                    </td>
                    <td class="px-6 py-4 text-slate-700 text-center">
                        <span class="text-slate-400">{{ $hd->chisodau }}</span> 
                        <i class="fa-solid fa-arrow-right text-slate-300 mx-1"></i> 
                        <span class="text-orange-500 font-bold">{{ $hd->chisocuoi }}</span>
                    </td>
                    <td class="px-6 py-4 text-right font-black text-red-600">
                        {{ number_format($hd->tongtien, 0, ',', '.') }} ₫
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($hd->trangthai_thanhtoan == 1)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">Đã thanh toán</span>
                        @else
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold border border-orange-200">Chưa thu tiền</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('hoa-don.chi-tiet', $hd->mahd) }}" class="text-blue-500 hover:bg-blue-50 px-3 py-2 rounded-lg border border-slate-100 transition inline-block">
                            <i class="fa-solid fa-eye mr-1"></i> Xem hóa đơn
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($danhSachHD->isEmpty())
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Chưa có hóa đơn nào được lập.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function autoFillChiSoDau(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var chiSoDau = selectedOption.getAttribute('data-chisodau');
        document.getElementById('chisodau').value = chiSoDau ? chiSoDau : 0;
    }
</script>
@endsection