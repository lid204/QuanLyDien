@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Hệ thống Quản lý tính tiền điện sinh hoạt</h2>

    <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-6">
        <h3 class="text-sm font-bold text-blue-900 mb-3"><i class="fa-solid fa-magnifying-glass"></i> Tra cứu hóa đơn</h3>
        <div class="flex space-x-2">
            <input type="text" id="mahd_input" placeholder="Nhập mã hóa đơn (VD: HD202602)" class="w-64 rounded outline-none focus:ring-1 focus:ring-blue-400">
            <button onclick="searchInvoice()" class="bg-gray-200 border border-gray-400 px-4 py-1 text-sm font-semibold hover:bg-gray-300 rounded shadow-sm">
                Thực hiện
            </button>
        </div>
    </div>

    @if(!Session::has('user'))
    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded text-sm text-yellow-800">
        <i class="fa-solid fa-triangle-exclamation"></i> Vui lòng <strong>đăng nhập</strong> với quyền Nhân viên để truy cập các chức năng quản lý, tính tiền và xem lịch sử giá điện 2 năm.
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border p-4 rounded bg-white shadow-sm">
            <div class="text-xs text-gray-500 uppercase">Hóa đơn chưa thu</div>
            <div class="text-2xl font-bold text-red-600">12</div>
        </div>
        <div class="border p-4 rounded bg-white shadow-sm">
            <div class="text-xs text-gray-500 uppercase">Khách hàng mới (Tháng)</div>
            <div class="text-2xl font-bold text-blue-600">05</div>
        </div>
        <div class="border p-4 rounded bg-white shadow-sm">
            <div class="text-xs text-gray-500 uppercase">Tổng doanh thu kỳ 02</div>
            <div class="text-2xl font-bold text-green-600">210.150đ</div>
        </div>
    </div>
    @endif
</div>

<script>
function searchInvoice() {
    const mahd = document.getElementById('mahd_input').value;
    if(!mahd) return alert('Vui lòng nhập mã!');

    fetch('/api/invoices/' + mahd)
        .then(res => res.json())
        .then(data => {
            if(data.mahd) {
                alert("KẾT QUẢ TRA CỨU:\n- Mã HD: " + data.mahd + "\n- Kỳ: " + data.ky + "\n- Tổng tiền: " + Number(data.tongthanhtien).toLocaleString() + " VNĐ");
            } else {
                alert("Không tìm thấy dữ liệu.");
            }
        })
        .catch(() => alert("Lỗi kết nối API!"));
}
</script>
@endsection