<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_don';
    protected $primaryKey = 'mahd';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'mahd', 'makh', 'madk', 'manv', 'thang_nam', 'chisodau', 'chisocuoi', 'tongtien', 'trangthai_thanhtoan'
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'makh', 'makh');
    }

    public function dienKe()
    {
        return $this->belongsTo(DienKe::class, 'madk', 'madk');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'manv', 'manv');
    }

    public function chiTietHoaDons()
    {
        return $this->hasMany(CtHoaDon::class, 'mahd', 'mahd');
    }
}