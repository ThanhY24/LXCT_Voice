<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_bienlai';

    protected $fillable = [
        'ma_kythi',
        'ma_hocvien',
        'sotienthu_bienlai',
        'loai_bienlai'
    ];

    public function kyThi()
    {
        return $this->belongsTo(ExaminationsModel::class, 'ma_kythi', 'id');
    }

    public function hocVien()
    {
        return $this->belongsTo(StudentModel::class, 'ma_hocvien', 'id');
    }
}
