<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadTempModel;
use App\Models\StudentModel;
use App\Models\ExaminationsModel;

class ReadTempController extends Controller
{
    public function showReadTemp(Request $request){
        return view("main-read");
    }
    public function getData(Request $request){
        $idExaminations = $request->input("idExaminations");
        $codeExaminations = ExaminationsModel::where("id", $idExaminations)->first()["ma_kythi"];
        $dataReadTemp = ReadTempModel::where("ma_kythi", $idExaminations)->where("dadoc", 0)->orderby("stt", "asc")->get();
        return returnJSON([
            "dataReadTemp" => $dataReadTemp,
            "codeExaminations" => $codeExaminations,
        ]);
    }
    public function updateReadTemp(Request $request){
        $idReadTemp = $request->input("idReadTemp");
        $data = ReadTempModel::where('id', $idReadTemp)->update([
            "dadoc" => 1
        ]);
        if ($data) {
            return response()->json(['message' => 'Cập nhật thành công']);
        } else {
            return response()->json(['message' => 'Cập nhật không thành công'], 500);
        }
    }
    public function createReadTemp(Request $request){
        $dataReadTemp = $request->input("dataRead");
        $maxSTT = ReadTempModel::where("ma_kythi", $dataReadTemp["ma_kythi"])->max('stt');
        if($maxSTT == null){
            $maxSTT = 1;
        }else{
            $maxSTT = $maxSTT + 1;
        }
        if(ReadTempModel::create([
            'stt' => $maxSTT,
            'hoten_hocvien' => $dataReadTemp["hoten_hocvien"],
            'sobaodanh_hocvien' => $dataReadTemp["sobaodanh_doc"],
            'voice_hocvien' => $dataReadTemp["hoc_vien"]["voice_hocvien"],
            'voice_loaidoc' => $dataReadTemp["ma_loaidoc"] == 1 ? "lythuyet.mp3" : "mophong.mp3",
            'dadoc' => 0,
            'ma_kythi' => $dataReadTemp["ma_kythi"],
        ])){
            return returnJSON([
                "message" => "Thành công"
            ]);
        }else{
            return returnJSON([
                "message" => "Có lỗi khi thao tác"
            ]);
        }
    }
    public function addToTop(Request $request){
        $idRegistration = $request->input("idRegistration");
        $idExaminations = $request->input("idExaminations");
        $typeRead = $request->input("typeRead");
        if($idExaminations == null){
            $idExaminations = ExaminationsModel::where("trangthai_kythi", 1)->orderby("created_at", "asc")->first()["id"];
        }
        $dataStudent = StudentModel::where("sobaodanh_hocvien", $idRegistration)->where("ma_kythi", $idExaminations)->first();
        ReadTempModel::where("ma_kythi", $idExaminations)->increment('stt');
        if(ReadTempModel::create([
            'stt' => 1,
            'hoten_hocvien' => $dataStudent["hoten_hocvien"],
            'sobaodanh_hocvien' => $idRegistration,
            'voice_hocvien' => $dataStudent["voice_hocvien"],
            'voice_loaidoc' => $typeRead.".mp3",
            'dadoc' => 0,
            'ma_kythi' => $idExaminations,
        ])){
            return returnJSON([
                "message" =>"Đã thêm học viên lên đầu danh sách"
            ]);
        }else{
            return returnJSON([
                "message" =>"Có lỗi xảy ra"
            ]);
        }
    }
}
