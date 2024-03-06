<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigurartionModel;

class ConfigurationController extends Controller
{
    public function getDataConfig(){
        return returnJSON([
            "dataConfig" => ConfigurartionModel::first()
        ]);
    }   
    public function test(){
        $string  = "042095000179|285663232";
        $stringArr = explode("|", $string);
        echo count($stringArr);
    }
}
