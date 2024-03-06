<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadTempModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_read_temp';

    protected $fillable = [
        'stt',
        'hoten_hocvien',
        'sobaodanh_hocvien',
        'voice_hocvien',
        'voice_loaidoc',
        'dadoc',
        'ma_kythi',
    ];

    public function kythi()
    {
        return $this->belongsTo(Kythi::class, 'ma_kythi');
    }
}