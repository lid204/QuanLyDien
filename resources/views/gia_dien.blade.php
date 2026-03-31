@extends('layouts.app')
@section('title', 'Lịch sử thay đổi giá điện')

@section('content')
<div class="w-full mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Lịch sử thay đổi giá điện</h2>
            <p class="text-slate-400 text-sm italic">Hiển thị các bản ghi cập nhật trong 2 năm gần nhất</p>
        </div>
        <a href="/dashboard" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-bold text-xs hover:bg-slate-300 transition">
            <i class="fa-solid fa-xmark mr-2"></i>Thoát
        </a>
    </div>

    @if($hasData)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Bậc Điện</th>
                        <th class="px-6 py-4 text-right">Đơn giá</th>
                        <th class="px-6 py-4 text-center">Ngày thay đổi</th>
                        <th class="px-6 py-4 text-center">Người thực hiện</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($lichSuGia as $gia)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $gia->bacDien->tenbac }}</td>
                        <td class="px-6 py-4 text-right font-black text-blue-600">{{ number_format($gia->dongia) }} đ</td>
                        <td class="px-6 py-4 text-center">{{ date('d/m/Y', strtotime($gia->ngayapdung)) }}</td>
                        <td class="px-6 py-4 text-center text-slate-500">{{ $gia->nhanVien->tennv ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($gia->trangthai == 1)
                                <span class="text-green-600 font-bold">Hiện hành</span>
                            @else
                                <span class="text-slate-400">Đã đổi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-orange-50 border border-orange-200 p-10 rounded-2xl text-center">
            <i class="fa-solid fa-folder-open text-4xl text-orange-200 mb-4"></i>
            <p class="text-orange-700 font-medium">Không có lịch sử thay đổi giá trong 2 năm qua</p>
            <p class="text-orange-400 text-xs mt-1">Dữ liệu cập nhật sẽ hiển thị tại đây sau khi có thay đổi.</p>
        </div>
    @endif
</div>
@endsection