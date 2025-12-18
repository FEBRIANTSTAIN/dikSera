<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSertifikat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lisensiLama() {
        return $this->belongsTo(PerawatLisensi::class, 'lisensi_lama_id');
    }

    public function jadwalWawancara() {
    return $this->hasOne(JadwalWawancara::class, 'pengajuan_sertifikat_id')->latest();
} 

    public function penanggungJawab() {
        return $this->belongsTo(PenanggungJawabUjian::class, 'penanggung_jawab_id');
    }
}
