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
        Schema::create('tbl_bienlai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ma_kythi');
            $table->unsignedBigInteger('ma_hocvien');
            $table->string('sotienthu_bienlai');
            $table->string('loai_bienlai');
            $table->timestamps();

            // Thêm khóa ngoại
            $table->foreign('ma_kythi')->references('id')->on('tbl_kythi');
            $table->foreign('ma_hocvien')->references('id')->on('tbl_hocvien');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bienlai');
    }
};
