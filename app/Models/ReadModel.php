<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_doc';

    protected $fillable = [
        'stt_doc', 'ma_loaidoc', 'ma_hocvien', 'ma_kythi', 'uutien_doc', 'sobaodanh_doc', 'hoten_hocvien', 'danhap_hocvien'
    ];

    public function loaiDoc()
    {
        return $this->belongsTo(TypeReadModel::class, 'ma_loaidoc', 'id');
    }

    public function hocVien()
    {
        return $this->belongsTo(StudentModel::class, 'ma_hocvien', 'id');
    }
}
