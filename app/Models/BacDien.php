<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacDien extends Model
{
    protected $table = 'bac_dien';
    protected $primaryKey = 'mabac';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'mabac', 'tenbac', 'tuky', 'denky'
    ];

    public function chiTietHoaDons()
    {
        return $this->hasMany(CtHoaDon::class, 'mabac', 'mabac');
    }

    public function lichSuGias()
    {
        return $this->hasMany(LichSuGia::class, 'mabac', 'mabac');
    }
}