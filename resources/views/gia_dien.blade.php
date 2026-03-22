<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử Giá điện</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-900 text-white p-4 flex justify-between">
        <h1 class="font-bold">TRA CỨU GIÁ ĐIỆN</h1>
        <a href="/dashboard" class="text-sm bg-gray-700 px-3 py-1 rounded">Quay lại Dashboard</a>
    </nav>

    <div class="p-8">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-blue-800 italic">Bảng giá điện qua các năm</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-sm">
                        <th class="border p-3 text-left">Tên Bậc</th>
                        <th class="border p-3 text-right">Đơn giá (VNĐ)</th>
                        <th class="border p-3 text-center">Ngày áp dụng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lichSuGia as $gia)
                    <tr class="hover:bg-blue-50">
                        <td class="border p-3 font-semibold">{{ $gia->ten_bac }}</td>
                        <td class="border p-3 text-right text-red-600 font-bold">{{ number_format($gia->don_gia, 0, ',', '.') }}</td>
                        <td class="border p-3 text-center">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                {{ date('d/m/Y', strtotime($gia->ngay_apdung)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>