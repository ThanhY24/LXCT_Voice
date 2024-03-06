<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\StudentModel;
use App\Models\ExaminationsModel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $idExaminationsMostRecent = ExaminationsModel::where("trangthai_kythi", 1)->orderby("created_at", "asc")->first()["id"];
            $directory = ExaminationsModel::where("trangthai_kythi", 1)->orderby("created_at", "asc")->first()["ma_kythi"];
            $dataStudent = StudentModel::where("ma_kythi", $idExaminationsMostRecent)->get();
            foreach($dataStudent as $key => $student){
                if($student["voice_hocvien"] == null){
                    $stringVoice = "Mời " . $student["hoten_hocvien"] . " có số báo danh " .$student["sobaodanh_hocvien"] ." vào phòng thi";
                    $path = 'audios/examinations/' . $directory . "/";
                    downloadVoiceFromURL(createVoiceFromFPT($stringVoice), $path, $student["id"] . ".mp3");
                    $student->update(['voice_hocvien' => $student["id"] . ".mp3"]);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
