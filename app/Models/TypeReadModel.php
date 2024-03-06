<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeReadModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_loaidoc';

    protected $fillable = [
        'ten_loaidoc', 'chuoi_loaidoc', 'voice_loaidoc'
    ];
}
