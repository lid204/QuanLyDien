<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtHoaDon extends Model
{
    protected $table = 'ct_hoa_don';
    // Khóa chính kép (mahd, mabac) nên bỏ trống $primaryKey và tắt incrementing
    public $incrementing = false; 

    public $timestamps = false;

    protected $fillable = [
        'mahd', 'mabac', 'soky', 'dongia', 'thanhtien'
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'mahd', 'mahd');
    }

    public function bacDien()
    {
        return $this->belongsTo(BacDien::class, 'mabac', 'mabac');
    }
}