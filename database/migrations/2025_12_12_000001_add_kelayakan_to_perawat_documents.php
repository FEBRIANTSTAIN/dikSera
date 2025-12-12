<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelayakanToPerawatDocuments extends Migration
{
    public function up()
    {
        Schema::table('perawat_strs', function (Blueprint $table) {
            $table->string('kelayakan', 20)->nullable()->default('pending')->after('file_path');
        });
        Schema::table('perawat_sips', function (Blueprint $table) {
            $table->string('kelayakan', 20)->nullable()->default('pending')->after('file_path');
        });
        Schema::table('perawat_lisensis', function (Blueprint $table) {
            $table->string('kelayakan', 20)->nullable()->default('pending')->after('file_path');
        });
        Schema::table('perawat_data_tambahans', function (Blueprint $table) {
            $table->string('kelayakan', 20)->nullable()->default('pending')->after('file_path');
        });
    }

    public function down()
    {
        Schema::table('perawat_strs', function (Blueprint $table) {
            $table->dropColumn('kelayakan');
        });
        Schema::table('perawat_sips', function (Blueprint $table) {
            $table->dropColumn('kelayakan');
        });
        Schema::table('perawat_lisensis', function (Blueprint $table) {
            $table->dropColumn('kelayakan');
        });
        Schema::table('perawat_data_tambahans', function (Blueprint $table) {
            $table->dropColumn('kelayakan');
        });
    }
}
