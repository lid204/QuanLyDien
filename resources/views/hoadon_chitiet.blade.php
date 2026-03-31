@extends('layouts.app')
@section('title', 'Chi tiết Hóa đơn ' . $hoaDon->mahd)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200">
    <div class="text-center border-b pb-4 mb-6">
        <h1 class="text-3xl font-bold text-blue-700 uppercase">Hóa Đơn Tiền Điện</h1>
        <p class="text-gray-500 mt-2">Kỳ Hóa Đơn: <span class="font-semibold text-gray-800">{{ date('m/Y', strtotime($hoaDon->thang_nam)) }}</span></p>
        <p class="text-gray-500">Mã Hóa Đơn: {{ $hoaDon->mahd }}</p>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div class="bg-blue-50 p-4 rounded-md">
            <h2 class="font-bold text-blue-800 mb-2 border-b border-blue-200 pb-1 uppercase text-sm">Thông tin khách hàng</h2>
            <p class="mb-1 text-sm"><span class="text-gray-600">Mã KH:</span> <span class="font-medium">{{ $hoaDon->makh }}</span></p>
            <p class="mb-1 text-sm"><span class="text-gray-600">Khách hàng:</span> <span class="font-medium">{{ $hoaDon->khachHang->tenkh }}</span></p>
            <p class="mb-1 text-sm"><span class="text-gray-600">Địa chỉ:</span> <span class="font-medium">{{ $hoaDon->khachHang->diachi }}</span></p>
            <p class="text-sm"><span class="text-gray-600">Điện kế:</span> <span class="font-medium">{{ $hoaDon->madk }}</span></p>
        </div>

        <div class="bg-orange-50 p-4 rounded-md">
            <h2 class="font-bold text-orange-800 mb-2 border-b border-orange-200 pb-1 uppercase text-sm">Chỉ số tiêu thụ</h2>
            <p class="mb-1 text-sm"><span class="text-gray-600">Chỉ số cũ (Đầu):</span> <span class="font-medium">{{ number_format($hoaDon->chisodau) }}</span></p>
            <p class="mb-1 text-sm"><span class="text-gray-600">Chỉ số mới (Cuối):</span> <span class="font-medium text-red-600">{{ number_format($hoaDon->chisocuoi) }}</span></p>
            <p class="mt-2 text-lg"><span class="font-bold text-gray-800">Điện năng tiêu thụ:</span> <span class="font-bold text-orange-600">{{ $hoaDon->chisocuoi - $hoaDon->chisodau }} kWh</span></p>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="font-bold text-gray-800 mb-3 uppercase tracking-tight">Chi tiết tính tiền điện bậc thang</h2>
        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-[#1e293b] text-white">
                    <th class="p-3 border border-slate-300">Bậc Điện</th>
                    <th class="p-3 border border-slate-300 text-center">Đơn Giá</th>
                    <th class="p-3 border border-slate-300 text-center">Số Ký</th>
                    <th class="p-3 border border-slate-300 text-right">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                @php $tongChuaThue = 0; @endphp
                @foreach($hoaDon->chiTietHoaDons as $ct)
                <tr class="hover:bg-gray-50 divide-x divide-slate-200">
                    <td class="p-3 border border-slate-200 font-medium text-slate-700">
                        {{ $ct->bacDien->tenbac }} ({{ $ct->bacDien->tuky }} - {{ $ct->bacDien->denky == 999999 ? 'trở lên' : $ct->bacDien->denky }} kWh)
                    </td>
                    <td class="p-3 border border-slate-200 text-center text-slate-600">
                        {{ number_format($ct->dongia, 0, ',', '.') }} đ
                    </td>
                    <td class="p-3 border border-slate-200 text-center font-bold text-slate-800">
                        {{ $ct->soky }}
                    </td>
                    <td class="p-3 border border-slate-200 text-right font-medium">
                        {{ number_format($ct->thanhtien, 0, ',', '.') }} đ
                    </td>
                </tr>
                @php $tongChuaThue += $ct->thanhtien; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mb-8">
        <div class="w-full max-w-md bg-gray-50 p-6 rounded-xl border border-slate-200">
            <div class="flex justify-between mb-2 text-slate-600">
                <span>Cộng tiền điện:</span>
                <span class="font-semibold text-slate-800">{{ number_format($tongChuaThue, 0, ',', '.') }} đ</span>
            </div>
            <div class="flex justify-between mb-2 text-slate-600">
                <span>Thuế GTGT (10%):</span>
                <span class="font-semibold text-slate-800">{{ number_format($tongChuaThue * 0.1, 0, ',', '.') }} đ</span>
            </div>
            <div class="flex justify-between mt-4 pt-4 border-t border-gray-300 items-center">
                <span class="font-black text-slate-900 uppercase tracking-wider">Tổng thanh toán:</span>
                <span class="font-black text-3xl text-red-600 italic">
                    {{ number_format($hoaDon->tongtien, 0, ',', '.') }} đ
                </span>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-4 border-t pt-6 no-print">
        <a href="/hoa-don" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Quay lại
        </a>
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md shadow-blue-200">
            <i class="fa-solid fa-print mr-2"></i>In Hóa Đơn / PDF
        </button>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none; }
        body { background: white; padding: 0; }
        .max-w-4xl { border: none; box-shadow: none; width: 100%; max-width: 100%; }
        main { margin: 0 !important; padding: 0 !important; }
        aside { display: none; }
    }
</style>
@endsection