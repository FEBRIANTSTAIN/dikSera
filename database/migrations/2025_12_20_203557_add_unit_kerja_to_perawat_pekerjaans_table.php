<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitKerjaToPerawatPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('perawat_pekerjaans', function (Blueprint $table) {
        $table->string('unit_kerja', 150)->nullable()->after('nama_instansi');
    });
}

public function down()
{
    Schema::table('perawat_pekerjaans', function (Blueprint $table) {
        $table->dropColumn('unit_kerja');
    });
}
}
