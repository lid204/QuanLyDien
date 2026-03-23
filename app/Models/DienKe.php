<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienKe extends Model
{
    protected $table = 'dien_ke';
    protected $primaryKey = 'madk';
    public $incrementing = false;
    protected $keyType = 'string';

    
    public $timestamps = false;

    protected $fillable = ['madk', 'makh', 'diachi_lapdat', 'ngaysx', 'ngaylap', 'trangthai'];
}