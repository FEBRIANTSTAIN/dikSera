<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalWawancara extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_wawancara' => 'datetime',
    ];

    public function pengajuan() {
        return $this->belongsTo(PengajuanSertifikat::class, 'pengajuan_sertifikat_id');
    }

    public function pewawancara() {
        return $this->belongsTo(PenanggungJawabUjian::class, 'penanggung_jawab_id');
    }

    public function penilaian() {
        return $this->hasOne(WawancaraPenilaian::class, 'jadwal_wawancara_id');
    }
}
