<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawabUjian extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_ujians';

    protected $fillable = [
        'user_id',
        'nama',
        'no_hp',
        'jabatan',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalWawancara()
    {
        return $this->hasMany(JadwalWawancara::class, 'penanggung_jawab_id');
    }
}
