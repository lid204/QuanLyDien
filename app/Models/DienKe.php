<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienKe extends Model
{
    protected $table = 'dien_ke';
    protected $primaryKey = 'madk';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Tắt hoàn toàn timestamps vì bảng này không có created_at và updated_at
    public $timestamps = false; 

    protected $fillable = [
        'madk', 'makh', 'ngaylap', 'trangthai', 'diachi_lapdat'
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'makh', 'makh');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'madk', 'madk');
    }
}