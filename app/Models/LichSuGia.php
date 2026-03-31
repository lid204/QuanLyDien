<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuGia extends Model
{
    protected $table = 'lich_su_gia';
    protected $primaryKey = 'magia';
    // Bảng này khóa chính là INT AUTO_INCREMENT nên tự động tăng, không cần thiết lập thêm

    public $timestamps = false;

    protected $fillable = [
        'mabac', 'manv', 'dongia', 'ngayapdung', 'trangthai'
    ];

    public function bacDien()
    {
        return $this->belongsTo(BacDien::class, 'mabac', 'mabac');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'manv', 'manv');
    }
}