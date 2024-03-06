<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Models\InvoicesModel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoicesController extends Controller
{
    public function createInv(Request $request){
        if($request->has('idRegistration') && $request->has('typeRead') && $request->has("idExaminations")){
            $idExaminations = $request->input('idExaminations');
            // $ma_hocvien = StudentModel::where(function($query) use ($request) {
            //     $query->where("sobaodanh_hocvien", $request->input('idRegistration'))
            //           ->orWhere("madangky_hocvien", $request->input('idRegistration'))
            //           ->orWhere("sogiayto_hocvien", $request->input('idRegistration'));
            // })
            // ->where("ma_kythi", $request->input('idExaminations'))
            // ->first()["id"];
            $ma_hocvien = 0;
            $inputIDofStudent = $request->input("idRegistration");
            $dataStudent = [];
            if(count(explode("|", $inputIDofStudent)) == 2){
                $ids = explode("|", $inputIDofStudent);
                $ma_hocvien = StudentModel::where(function ($query) use ($ids) {
                    $query->orWhere("sogiayto_hocvien", $ids);
                })->where("ma_kythi", $idExaminations)->first()["id"];
            }else{
                $ma_hocvien = StudentModel::where(function($query) use ($inputIDofStudent) {
                    $query->where("sobaodanh_hocvien", $inputIDofStudent)
                          ->orWhere("sogiayto_hocvien", $inputIDofStudent)
                          ->orWhere("madangky_hocvien", $inputIDofStudent);
                })
                ->where("ma_kythi", $idExaminations)
                ->first()["id"];
            }
            $ma_kythi = $idExaminations;
            $sotienthu_bienlai = 100000;
            $loai_bienlai = $request->input('typeRead');
            $newInvoice = InvoicesModel::create([
                'ma_kythi' => $ma_kythi,
                'ma_hocvien' => $ma_hocvien,
                'sotienthu_bienlai' => $sotienthu_bienlai,
                'loai_bienlai' => $loai_bienlai,
            ]);
            $createdInvoice = InvoicesModel::with('kyThi', 'hocVien')->find($newInvoice->id);
            $amountInWords = number_to_word($createdInvoice["sotienthu_bienlai"]) . " đồng";
            return response()->json([
                'dataInvoice' => $createdInvoice,
                'amountInWords' => $amountInWords,
            ], 201);
        }else{
            return returnJSON([
                "message" => "Dữ liệu đầu vào không đúng"
            ]);
        }
    }
    public function getInvByInfo(Request $request){
        $idStudent = $request->input("idStudent");
        $idExaminations = $request->input("idExaminations");
        $type = $request->input("type");
        $dataInv = InvoicesModel::where("ma_hocvien", $idStudent)->where("ma_kythi", $idExaminations)
                            ->where("loai_bienlai", $type)
                            ->with('kyThi', 'hocVien')->first();
        if($dataInv != null){
            $amountInWords = number_to_word($dataInv["sotienthu_bienlai"]) . " đồng";
            return response()->json([
                'dataInvoice' => $dataInv,
                'amountInWords' => $amountInWords,
            ], 201);
        }else{
            return returnJSON([
                "message" => "Không có biên lai"
            ]);
        }
    }
    public function reportInv(Request $request){
        if ($request->has("exportExcel")) {
            $invoices = InvoicesModel::where('ma_kythi', $request->input("idExaminations"))
                ->with(['hocVien'])
                ->get();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'STT');
            $sheet->setCellValue('B1', 'Số báo danh');
            $sheet->setCellValue('C1', 'Tên học viên');
            $sheet->setCellValue('D1', 'Lý thuyết');
            $sheet->setCellValue('E1', 'Mô phỏng');
            $sheet->setCellValue('F1', 'Thực hành');
            $sheet->setCellValue('G1', 'Đường trường');
            $sheet->setCellValue('H1', 'Tổng tiền');
            $sheet->setCellValue('I1', 'Ngày thu');
            $row = 2;
            $stt = 1;
            foreach ($invoices as $invoice) {
                $sheet->setCellValue('A' . $row, $stt);
                $sheet->setCellValue('B' . $row, $invoice->hocVien->sobaodanh_hocvien);
                $sheet->setCellValue('C' . $row, $invoice->hocVien->hoten_hocvien);
                $sheet->setCellValue('D' . $row, number_format($invoice->sotienthu_bienlai));
                $sheet->setCellValue('E' . $row, "");
                $sheet->setCellValue('F' . $row, "");
                $sheet->setCellValue('G' . $row, "");
                $sheet->setCellValue('H' . $row, number_format($invoice->sotienthu_bienlai));
                $sheet->setCellValue('I' . $row, $invoice->created_at->format('d/m/Y'));
                $row++;
                $stt++;
            }
            $excelFolder = 'excel';
            Storage::makeDirectory($excelFolder);
            $filename = 'export_' . $request->input("idExaminations") . '.xlsx';
            $filePath = $excelFolder . '/' . $filename;
            $writer = new Xlsx($spreadsheet);
            $writer->save(storage_path('app/' . $filePath));
            return response()->download(storage_path('app/' . $filePath));
        }
        else{
            $dataInv = InvoicesModel::where("ma_kythi", $request->input("idExaminations"))->with("hocVIen", "kyThi")->get();
            return view("report")->with([
                "dataInv" => $dataInv,
                "idExaminations" => $request->input("idExaminations"),
            ]);
        }
    }
}
