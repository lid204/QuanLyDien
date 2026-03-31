<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model 
{
    protected $table = 'khach_hang'; 
    protected $primaryKey = 'makh'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 

    // Bảng chỉ có created_at, không có updated_at
    const UPDATED_AT = null;

    // Danh sách các cột được phép thêm/sửa tự động
    protected $fillable = [
        'makh', 'tenkh', 'email', 'diachi', 'dt', 'cmnd', 'password'
    ];

    public function dienKes()
    {
        return $this->hasMany(DienKe::class, 'makh', 'makh');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'makh', 'makh');
    }
}