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
        Schema::create('tbl_read_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('stt');
            $table->string('hoten_hocvien');
            $table->string('sobaodanh_hocvien');
            $table->string('voice_hocvien');
            $table->string('voice_loaidoc');
            $table->string('dadoc');
            $table->unsignedBigInteger('ma_kythi');
            $table->foreign('ma_kythi')->references('id')->on('tbl_kythi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_read_temp');
    }
};
