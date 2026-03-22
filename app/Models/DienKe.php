<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienKe extends Model
{
    protected $table = 'dien_ke';
    protected $primaryKey = 'madk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['madk', 'makh', 'ngaysx', 'ngaylap', 'trangthai'];
}