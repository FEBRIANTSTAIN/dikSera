<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerawatTandajasaTable extends Migration
{
    public function up()
    {
        Schema::create('perawat_tandajasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('nama_penghargaan', 150)->nullable();
            $table->string('pemberi', 150)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('keterangan', 255)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perawat_tandajasa');
    }
}
