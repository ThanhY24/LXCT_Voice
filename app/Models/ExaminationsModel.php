<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_kythi';
    protected $fillable = [
        'ma_kythi', 'ten_kythi', 'ngaythi_kythi', 'giothi_kythi', 'trangthai_kythi', 'in_bienlai'
    ];
}
