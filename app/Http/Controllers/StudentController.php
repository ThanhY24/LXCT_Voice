<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Models\ExaminationsModel;

class StudentController extends Controller
{
    public function getStudent(Request $request){
        $idRegistration = $request->input("idRegistration");
        $idExaminations = $request->input("idExaminations");
        $directory = ExaminationsModel::where("id", $idExaminations)->first()["ma_kythi"];
        $dataStudent = StudentModel::where("sobaodanh_hocvien", $idRegistration)->where("ma_kythi", $idExaminations)
                                    ->with("kyThi")
                                    ->first();
        return response()->json([
            "dataStudent" => $dataStudent,
            "directory" => $directory,
        ]);
    }
}
