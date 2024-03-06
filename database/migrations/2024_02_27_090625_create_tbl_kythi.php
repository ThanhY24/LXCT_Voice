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
        Schema::create('tbl_kythi', function (Blueprint $table) {
            $table->id();
            $table->string('ma_kythi');
            $table->string('ten_kythi');
            $table->date('ngaythi_kythi');
            $table->string('giothi_kythi');
            $table->integer('trangthai_kythi');
            $table->integer('in_bienlai');
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
        Schema::dropIfExists('tbl_kythi');
    }
};
