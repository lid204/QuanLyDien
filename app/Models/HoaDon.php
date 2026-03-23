<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DienKe; 

class HoaDon extends Model
{
    protected $table = 'hoa_don';
    protected $primaryKey = 'mahd'; 
    public $incrementing = false;
    protected $keyType = 'string';

    
    public function dienKe()
    {
        return $this->belongsTo(DienKe::class, 'madk', 'madk');
    }
}