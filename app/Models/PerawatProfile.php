<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerawatProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'alamat',
        'kota',
        'no_hp',
        'golongan_darah',
        'tinggi_badan',
        'berat_badan',
        'foto_3x4',           // <-- TAMBAH INI
    ];
}
