<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WawancaraPenilaian extends Model
{
    protected $guarded = [];
    public function jadwal() {
        return $this->belongsTo(JadwalWawancara::class, 'jadwal_wawancara_id');
    }
}
