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
        Schema::create('tbl_configuration', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('hotline')->nullable();
            $table->string('short_name')->nullable();
            $table->string('url')->nullable();;
            $table->string('dat_url')->nullable();
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('QRCodeBank')->nullable();
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
        Schema::dropIfExists('tbl_configuration');
    }
};
