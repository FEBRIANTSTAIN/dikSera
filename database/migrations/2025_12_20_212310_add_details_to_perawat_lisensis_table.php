<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToPerawatLisensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('perawat_lisensis', function (Blueprint $table) {
        $table->string('bidang')->nullable()->after('nomor'); // Sesuaikan posisi
        $table->string('kfk')->nullable()->after('bidang');
        $table->date('tgl_mulai')->nullable()->after('kfk');
        $table->date('tgl_diselenggarakan')->nullable()->after('tgl_mulai');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perawat_lisensis', function (Blueprint $table) {
            //
        });
    }
}
