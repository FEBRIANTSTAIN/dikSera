<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoAndDokumenToPerawatTables extends Migration
{
    public function up()
    {
        // Foto 3x4 di profile
        // Schema::table('perawat_profiles', function (Blueprint $table) {
        //     $table->string('foto_3x4')->nullable()->after('berat_badan');
        // });

        // Dokumen di pendidikan
        Schema::table('perawat_pendidikans', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('tahun_lulus');
        });

        // Dokumen di pelatihan
        Schema::table('perawat_pelatihans', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('durasi');
        });

        // Dokumen di pekerjaan
        Schema::table('perawat_pekerjaans', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('keterangan');
        });

        // Dokumen di tanda jasa
        Schema::table('perawat_tandajasa', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('keterangan');
        });

        // Dokumen di organisasi
        Schema::table('perawat_organisasis', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('perawat_profiles', function (Blueprint $table) {
            $table->dropColumn('foto_3x4');
        });

        Schema::table('perawat_pendidikans', function (Blueprint $table) {
            $table->dropColumn('dokumen_path');
        });

        Schema::table('perawat_pelatihans', function (Blueprint $table) {
            $table->dropColumn('dokumen_path');
        });

        Schema::table('perawat_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('dokumen_path');
        });

        Schema::table('perawat_tandajasa', function (Blueprint $table) {
            $table->dropColumn('dokumen_path');
        });

        Schema::table('perawat_organisasis', function (Blueprint $table) {
            $table->dropColumn('dokumen_path');
        });
    }
}
