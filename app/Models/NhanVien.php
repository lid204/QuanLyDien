<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'nhan_vien'; 
    protected $primaryKey = 'manv'; 
    public $incrementing = false; 
    protected $keyType = 'string';
    
    
    public $timestamps = false; 

    protected $fillable = ['manv', 'tennv', 'email', 'password', 'vai_tro'];
}