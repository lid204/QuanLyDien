<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'nhan_vien';
    protected $primaryKey = 'manv';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected $fillable = [
        'manv', 'tennv', 'email', 'password'
    ];

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'manv', 'manv');
    }

    public function lichSuGias()
    {
        return $this->hasMany(LichSuGia::class, 'manv', 'manv');
    }
}