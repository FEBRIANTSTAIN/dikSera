<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSertifikatsTable extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lisensi_lama_id')->nullable()->constrained('perawat_lisensis')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->enum('metode', ['pg_only', 'pg_interview', 'interview_only'])->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('penanggung_jawab_ujians'); // Pembimbing
            $table->date('tgl_wawancara')->nullable();
            $table->string('lokasi_wawancara')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_sertifikats');
    }
}
