<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ReadModel;
use App\Models\StudentModel;
use App\Models\LoaiDocModel;
use Illuminate\Http\Request;
use App\Models\ExaminationsModel;
use Illuminate\Support\Facades\File;
class ExaminationsController extends Controller
{
    public function importExaminationsFromXML(Request $request){
        if($request->has("XMLFile")){
            $xmlFile = $request->file('XMLFile');
            $xmlContent = file_get_contents($xmlFile->path());
            $xml = simplexml_load_string($xmlContent);
            $examinations = [
                "ma_kythi" => (string)$xml->DATA->KY_SH->MAKYSH,
                "ten_kythi" => "Kỳ thi ngày " .  Carbon::createFromFormat('Y-m-d', (string)$xml->DATA->KY_SH->NGAYSH)->format('d/m/Y'),
                "ngaythi_kythi" => (string)$xml->DATA->KY_SH->NGAYSH,
                "giothi_kythi" => (string)$xml->DATA->KY_SH->GIOSH,
                "trangthai_kythi" => 1,
                "in_bienlai" => $request->has('isPrint') ? 1 : 0,
            ];
            $directoryPath = public_path('audios/examinations/'.$examinations["ma_kythi"]);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, $mode = 0777, true, true);
            }
            $examinations = ExaminationsModel::create($examinations);
            foreach ($xml->DATA->NGUOI_LXS->NGUOI_LX as $nguoiLx) {
                $student = [
                    "madangky_hocvien" => (string)$nguoiLx->MA_DK,
                    "sohoso_hocvien" => (string)$nguoiLx->HO_SO->SO_HO_SO,
                    "sobaodanh_hocvien" => (string)$nguoiLx->HO_SO->SO_BAO_DANH,
                    "hoten_hocvien" => (string)$nguoiLx->HO_VA_TEN,
                    "gioitinh_hocvien" => (string)$nguoiLx->GIOI_TINH,
                    "ngaysinh_hocvien" => Carbon::createFromFormat('Ymd', (string)$nguoiLx->NGAY_SINH),
                    "sogiayto_hocvien" => (string)$nguoiLx->SO_CMT,
                    "ketqualythuyet_hocvien" => (string)$nguoiLx->HO_SO->KQ_SH_LYTHUYET,
                    "ketquamophong_hocvien" => (string)$nguoiLx->HO_SO->KQ_SH_MOPHONG,
                    "macsdt_hocvien" => (string)$nguoiLx->HO_SO->MA_CSDT,
                    "mattsh_hocvien" => (string)$nguoiLx->HO_SO->MA_TTSH,
                    "magtvt_hocvien" => (string)$nguoiLx->HO_SO->MA_SO_GTVT,
                    "hanggplx_hocvien" => (string)$nguoiLx->HO_SO->HANG_GPLX,
                    "sonamlaixe_hocvien" => (string)$nguoiLx->HO_SO->SO_NAM_LAIXE,
                    "sokmantoan_hocvien" => (string)$nguoiLx->HO_SO->SO_KM_ANTOAN,
                    "sogiaycntn_hocvien" => (string)$nguoiLx->HO_SO->SO_GIAY_CNTN,
                    "soccn_hocvien" => (string)$nguoiLx->HO_SO->SO_CCN,
                    "noidungsathach_hocvien" => (string)$nguoiLx->HO_SO->NOI_DUNG_SH,
                    "ketquasahinh_hocvien" => (string)$nguoiLx->HO_SO->KQ_SH_HINH,
                    "ketquaduongtruong_hocvien" => (string)$nguoiLx->HO_SO->KQ_SH_DUONG,
                    "anh_hocvien" => "",
                    "ghichu_hocvien" => "",
                    "ma_kythi" => $examinations->id,
                ];
                StudentModel::create($student);
            }
            return redirect("/")->with([
                "type" => "OK",
                "message" => "Import Thành Công",
            ]);
        }else{
            return redirect("/")->with([
                "type" => "OK",
                "message" => "Chưa chọn file",
            ]);
        }
    }
}
