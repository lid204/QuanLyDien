<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DienKe; // Thêm dòng này để hết lỗi gạch chân đỏ

class HoaDon extends Model
{
    protected $table = 'hoa_don';
    protected $primaryKey = 'mahd'; // Mã số hóa đơn [cite: 56, 130]
    public $incrementing = false;
    protected $keyType = 'string';

    // Thiết lập quan hệ: Mỗi hóa đơn thuộc về một điện kế cụ thể [cite: 54, 128]
    public function dienKe()
    {
        return $this->belongsTo(DienKe::class, 'madk', 'madk');
    }
}