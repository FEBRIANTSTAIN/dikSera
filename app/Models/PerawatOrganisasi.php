<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerawatOrganisasi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama_organisasi',
        'jabatan',
        'tahun_mulai',
        'tahun_selesai',
        'keterangan',
         'dokumen_path', 
    ];

    /**
     * Get the user that owns the organization record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}