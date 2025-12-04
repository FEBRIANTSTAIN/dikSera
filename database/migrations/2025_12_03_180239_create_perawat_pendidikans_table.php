<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerawatPendidikansTable extends Migration
{
    public function up()
    {
        Schema::create('perawat_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('jenjang', 50)->nullable();      // D3, S1, dll
            $table->string('nama_institusi', 150)->nullable();
            $table->string('jurusan', 150)->nullable();
            $table->string('tahun_masuk', 4)->nullable();
            $table->string('tahun_lulus', 4)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perawat_pendidikans');
    }
}
