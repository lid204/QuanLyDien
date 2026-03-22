<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($mahd)
    {
        // Lấy hóa đơn kèm thông tin điện kế từ MySQL
        $invoice = HoaDon::with(['dienKe'])->where('mahd', $mahd)->first();

        if (!$invoice) {
            return response()->json(['message' => 'Không tìm thấy hóa đơn'], 404);
        }

        return response()->json($invoice);
    }
}