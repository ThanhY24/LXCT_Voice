<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Media\Concat;
use App\Models\ReadModel;
use App\Models\ReadTempModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use App\Models\TypeReadModel;
use App\Models\ExaminationsModel;
use Illuminate\Support\Facades\Log;
class ReadController extends Controller
{
    public function getDataRead(Request $request){
        $typeRead = $request->input("typeRead");
        $idTypeRead = 0;
        $typeRead === "lythuyet" ? $idTypeRead = 1 : $idTypeRead = 2;
        $idExaminations = $request->input("idExaminations");
        $codeExaminations = ExaminationsModel::where("id", $idExaminations)->first()["ma_kythi"];
        $dataRead = ReadModel::where("ma_kythi", $idExaminations)
                            ->where("ma_loaidoc", $idTypeRead)
                            ->where("danhap_hocvien", 0)
                            ->orderByDesc('uutien_doc')
                            ->orderBy('stt_doc')
                            ->with("hocVien")->get();
        $dataReturn = [
            "typeRead" => $typeRead,
            "codeExaminations" => $codeExaminations,
            "dataRead" => $dataRead,
        ];
        return returnJSON($dataReturn);
    }
    public function readShow(Request $request){
        $typeRead = $request->input("typeRead");
        return view("read")->with([
            "typeRead" => $typeRead
        ]);
    }
    public function  updateData(Request $request){
        $dataRead = $request->input('dataRead');
        $idStudentUpdatePriority = $request->input('idStudent');
        foreach ($dataRead as $item) {
            $id = $item['id'];
            $readModel = ReadModel::find($id);
            $readModel->stt_doc = $item['stt_doc'];
            if($item["id"] == $idStudentUpdatePriority){
                $readModel->uutien_doc = 0;
                $readModel->danhap_hocvien = 1;
            }
            $readModel->save();
        }
        return returnJSON([
            "message" => "Thành công"
        ]);
    }
    public function importShow(Request $request){
        $typeRead = 1;
        if($request->has("typeRead")){
            if($typeRead == "mophong"){
                $typeRead = 2;
            }else{
                $typeRead = 1;
            }
        }
        $idExaminationsMostRecent = ExaminationsModel::where("trangthai_kythi", 1)->orderby("created_at", "asc")->first()["id"];
        $isPrintINV = ExaminationsModel::where("trangthai_kythi", 1)->orderby("created_at", "asc")->first()["in_bienlai"];
        $typeRead = $request->input("typeRead");
        $dataRead = ReadModel::where("ma_kythi", $idExaminationsMostRecent)
                            ->where("ma_loaidoc", $typeRead)
                            ->orderby("stt_doc","asc")
                            ->with("hocVien")  
                            ->where("ma_loaidoc", 2)->get();
        return view("import")->with([
            "typeRead" => $request->input("typeRead"),
            "dataRead" => $dataRead,
            "isPrintINV" => $isPrintINV,
        ]);
    }
    public function import(Request $request){
        $typeRead = 1;
        $registrationStudent = 0;
        $idExaminations = $request->input("idExaminations");
        if(ExaminationsModel::where("id", $idExaminations)->first()["trangthai_kythi"] == 0){
            return returnJSON([
                "message" => "Khóa học đã đóng"
            ]);
        }
        $priority = $request->input('priority');
        if($request->has("typeRead") && $request->has("registrationStudent")){
            $typeRead = $request->input("typeRead") == "lythuyet" ? 1 : ($request->input("typeRead") == "mophong" ? 2 : (function() { session()->flash('message', 'Đường dẫn sai'); return redirect()->back(); })());
            $inputIDofStudent = $request->input("registrationStudent");
            $dataStudent = [];
            if(count(explode("|", $inputIDofStudent)) == 2){
                $ids = explode("|", $inputIDofStudent);
                $dataStudent = StudentModel::where(function ($query) use ($ids) {
                    $query->orWhere("sogiayto_hocvien", $ids);
                })->first();
            }else{
                $dataStudent = StudentModel::where(function($query) use ($inputIDofStudent) {
                    $query->where("sobaodanh_hocvien", $inputIDofStudent)
                          ->orWhere("sogiayto_hocvien", $inputIDofStudent)
                          ->orWhere("madangky_hocvien", $inputIDofStudent);
                })
                ->where("ma_kythi", $idExaminations)
                ->first();
            }
            if($dataStudent == null){
                return returnJSON([
                    "message" => "Học viên không tồn tại"
                ]);
            }
            $result = ReadModel::where("ma_kythi", $idExaminations)
                   ->where("ma_loaidoc", $typeRead)
                   ->get();
            if ($result->count() > 0) {
                if(ReadModel::where("sobaodanh_doc", $dataStudent["sobaodanh_hocvien"])->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->count() > 0){
                    // Có trong danh sách thực hiện chèn
                    if(ReadModel::where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->where("uutien_doc", 1)->count() > 0){
                        // Đã có danh sách ưu tiên trong csdl
                        $dataStudentInsert = ReadModel::where("sobaodanh_doc", $inputIDofStudent)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->first();
                        ReadModel::where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->where("uutien_doc", 1)->where("stt_doc", "<", $dataStudentInsert["stt_doc"])->increment("stt_doc", 1);
                        ReadModel::where("sobaodanh_doc", $inputIDofStudent)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->update([
                            "stt_doc" => 1,
                            "uutien_doc" => 1
                        ]);
                        return returnJSON([
                            "type" => "WARR",
                            "message" => "Chèn thành công"
                        ]);
                    }else{
                        // Chưa có danh sách ưu tiên trong csdl
                        $dataStudentInsert = ReadModel::where("sobaodanh_doc", $inputIDofStudent)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->first();
                        ReadModel::where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->where("uutien_doc", 0)->where("stt_doc", "<", $dataStudentInsert["stt_doc"])->increment("stt_doc", 1);
                        ReadModel::where("sobaodanh_doc", $inputIDofStudent)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->update([
                            "stt_doc" => 1,
                            "uutien_doc" => 0
                        ]);
                        return returnJSON([
                            "type" => "WARR",
                            "message" => "Chèn thành công"
                        ]);
                    }
                }else{
                    // Chưa có trong danh sách -> tạo mới
                    if($priority == 1){
                        // Có ưu tiên
                        if(ReadModel::where('uutien_doc', 1)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->count() > 0){
                            // Đã có học viên ưu tiên
                            ReadModel::where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->where("uutien_doc", 0)->increment('stt_doc');
                            $maxSTT = ReadModel::where('uutien_doc', 1)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->orderBy('stt_doc', 'desc')->first()["stt_doc"];
                            if(ReadModel::create([
                                'stt_doc' => $maxSTT + 1,
                                'ma_loaidoc' => $typeRead,
                                'ma_hocvien' => $dataStudent["id"],
                                'ma_kythi' => $idExaminations,
                                'uutien_doc' => $priority,
                                'hoten_hocvien' => $dataStudent["hoten_hocvien"],
                                'sobaodanh_doc' => $dataStudent["sobaodanh_hocvien"],
                            ])){
                                return returnJSON([
                                    "message" => "Thêm học viên thành công"
                                ]);
                            }else{
                                return returnJSON([
                                    "message" => "Có lỗi khi thêm học viên"
                                ]);
                            }
                        }else{
                            // Chưa có học viên ưu tiên
                            $maxSTT = ReadModel::where('uutien_doc', 0)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->orderBy('stt_doc', 'desc')->first()["stt_doc"];
                            ReadModel::where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->increment('stt_doc');
                            if(ReadModel::create([
                                'stt_doc' => $maxSTT,
                                'ma_loaidoc' => $typeRead,
                                'ma_hocvien' => $dataStudent["id"],
                                'ma_kythi' => $idExaminations,
                                'uutien_doc' => $priority,
                                'hoten_hocvien' => $dataStudent["hoten_hocvien"],
                                'sobaodanh_doc' => $dataStudent["sobaodanh_hocvien"],
                            ])){
                                return returnJSON([
                                    "message" => "Thêm học viên thành công"
                                ]);
                            }else{
                                return returnJSON([
                                    "message" => "Có lỗi khi thêm học viên"
                                ]);
                            }
                        }
                    }else{
                        // Không ưu tiên
                        // $maxSTT = ReadModel::where('uutien_doc', 0)->where("ma_kythi", $idExaminations)->where("ma_loaidoc", $typeRead)->orderBy('stt_doc', 'desc')->first()["stt_doc"];
                        $maxSTTRecord = ReadModel::where('uutien_doc', 0)
                                                ->where("ma_kythi", $idExaminations)
                                                ->where("ma_loaidoc", $typeRead)
                                                ->orderBy('stt_doc', 'desc')
                                                ->first();
                        $maxSTT = 0;
                        // Kiểm tra xem có bản ghi thỏa mãn điều kiện không
                        if ($maxSTTRecord) {
                            $maxSTT = $maxSTTRecord["stt_doc"];
                        }
                        if(ReadModel::create([
                            'stt_doc' => $maxSTT + 1,
                            'ma_loaidoc' => $typeRead,
                            'ma_hocvien' => $dataStudent["id"],
                            'ma_kythi' => $idExaminations,
                            'uutien_doc' => $priority,
                            'hoten_hocvien' => $dataStudent["hoten_hocvien"],
                            'sobaodanh_doc' => $dataStudent["sobaodanh_hocvien"],
                        ])){
                            return returnJSON([
                                "message" => "Thêm học viên thành công"
                            ]);
                        }else{
                            return returnJSON([
                                "message" => "Có lỗi khi thêm học viên"
                            ]);
                        }
                    }
                }
                return response()->json([
                    "data" => $dataStudent
                ]);
            } else {
                if(ReadModel::create([
                    'stt_doc' => 1,
                    'ma_loaidoc' => $typeRead,
                    'ma_hocvien' => $dataStudent["id"],
                    'ma_kythi' => $idExaminations,
                    'uutien_doc' => $priority,
                    'hoten_hocvien' => $dataStudent["hoten_hocvien"],
                    'sobaodanh_doc' => $dataStudent["sobaodanh_hocvien"],
                ])){
                    return returnJSON([
                        "message" => "Thêm học viên thành công"
                    ]);
                }else{
                    return returnJSON([
                        "message" => "Có lỗi khi thêm học viên"
                    ]);
                }
            }
        }else{
            session()->flash('message', 'Đường dẫn sai');
            return redirect()->back();
        }
    }
    public function getReadData(Request $request){
        $idExaminations = $request->input("idExaminations");
        $typeRead = $request->input("typeRead") == "lythuyet" ? 1 : 2; 
        $dataRead = ReadModel::where("ma_kythi", $idExaminations)
                            ->where("ma_loaidoc", $typeRead)
                            ->orderByDesc('uutien_doc')
                            ->orderBy('stt_doc')
                            ->with("hocVien")->get();
        return returnJSON([
            "dataRead" => $dataRead
        ]);
    }
}
