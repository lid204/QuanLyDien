<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model 
{
    protected $table = 'khach_hang'; 
    protected $primaryKey = 'makh'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 

    const UPDATED_AT = null;

    protected $fillable = ['makh', 'tenkh', 'diachi', 'dt', 'cmnd', 'password'];

    public function dienKe()
    {
        return $this->hasOne(DienKe::class, 'makh', 'makh');
    }
}