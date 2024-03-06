<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_hocvien';

    protected $fillable = [
        'madangky_hocvien', 'sohoso_hocvien', 'sobaodanh_hocvien', 'hoten_hocvien',
        'gioitinh_hocvien', 'ngaysinh_hocvien', 'sogiayto_hocvien', 'ketqualythuyet_hocvien',
        'ketquamophong_hocvien', 'macsdt_hocvien', 'mattsh_hocvien', 'magtvt_hocvien',
        'hanggplx_hocvien', 'sonamlaixe_hocvien', 'sokmantoan_hocvien', 'sogiaycntn_hocvien',
        'soccn_hocvien', 'noidungsathach_hocvien', 'ketquasahinh_hocvien',
        'ketquaduongtruong_hocvien', 'anh_hocvien', 'ghichu_hocvien', 'ma_kythi', 'voice_hocvien'
    ];

    protected $dates = ['ngaysinh_hocvien'];

    public function kyThi()
    {
        return $this->belongsTo(ExaminationsModel::class, 'ma_kythi', 'id');
    }
}
