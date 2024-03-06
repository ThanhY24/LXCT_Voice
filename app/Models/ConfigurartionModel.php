<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurartionModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_configuration';
    protected $fillable = [
        'code',
        'name',
        'address',
        'hotline',
        'short_name',
        'url',
        'dat_url',
        'icon',
        'logo',
        'QRCodeBank',
    ];
}
