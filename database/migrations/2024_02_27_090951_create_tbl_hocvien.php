<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_hocvien', function (Blueprint $table) {
            $table->id();
            $table->string('madangky_hocvien');
            $table->string('sohoso_hocvien');
            $table->string('sobaodanh_hocvien');
            $table->string('hoten_hocvien');
            $table->string('gioitinh_hocvien');
            $table->date('ngaysinh_hocvien');
            $table->string('sogiayto_hocvien');
            $table->string('ketqualythuyet_hocvien')->nullable();
            $table->string('ketquamophong_hocvien')->nullable();
            $table->string('macsdt_hocvien')->nullable();
            $table->string('mattsh_hocvien')->nullable();
            $table->string('magtvt_hocvien')->nullable();
            $table->string('hanggplx_hocvien')->nullable();
            $table->string('sonamlaixe_hocvien')->nullable();
            $table->string('sokmantoan_hocvien')->nullable();
            $table->string('sogiaycntn_hocvien')->nullable();
            $table->string('soccn_hocvien')->nullable();
            $table->string('noidungsathach_hocvien')->nullable();
            $table->string('ketquasahinh_hocvien');
            $table->string('ketquaduongtruong_hocvien')->nullable();
            $table->string('anh_hocvien')->nullable();
            $table->string('voice_hocvien')->nullable();
            $table->string('ghichu_hocvien')->nullable();
            $table->unsignedBigInteger('ma_kythi')->nullable();
            $table->timestamps();

            $table->foreign('ma_kythi')->references('id')->on('tbl_kythi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_hocvien');
    }
};
