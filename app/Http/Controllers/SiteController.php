<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use GuzzleHttp\Client;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $dataStudent = [];
        if ($request->has('idExaminations')) {
            $dataStudent = StudentModel::where("ma_kythi", $request->input('idExaminations'))
            ->orderBy('sobaodanh_hocvien')
            ->get();
        }

        return view("home")->with([
            "dataStudent" => $dataStudent
        ]);
    }
}
