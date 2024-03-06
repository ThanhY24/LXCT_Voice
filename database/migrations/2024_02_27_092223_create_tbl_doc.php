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
        Schema::create('tbl_doc', function (Blueprint $table) {
            $table->id();
            $table->integer('stt_doc');
            $table->unsignedBigInteger('ma_loaidoc');
            $table->unsignedBigInteger('ma_hocvien');
            $table->string('hoten_hocvien');
            $table->string('sobaodanh_doc');
            $table->integer('uutien_doc')->nullable();
            $table->unsignedBigInteger('ma_kythi');
            $table->string('danhap_hocvien')->default(0);
            $table->timestamps();

            $table->foreign('ma_loaidoc')->references('id')->on('tbl_loaidoc')->onDelete('cascade');
            $table->foreign('ma_hocvien')->references('id')->on('tbl_hocvien')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_doc');
    }
};
