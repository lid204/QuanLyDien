<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model 
{

    protected $table = 'khach_hang'; 


    protected $primaryKey = 'makh'; 


    public $incrementing = false; 
    protected $keyType = 'string'; 


    protected $fillable = ['makh', 'tenkh', 'diachi', 'dt', 'cmnd', 'password'];

}