<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerawatPendidikan extends Model
{
    protected $fillable = [
        'user_id',
        'jenjang',
        'nama_institusi',
        'jurusan',
        'tahun_masuk',
        'tahun_lulus',
        'dokumen_path',      // <-- TAMBAH
    ];
}
