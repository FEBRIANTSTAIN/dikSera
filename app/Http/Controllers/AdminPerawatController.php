<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PerawatProfile;
use App\Models\PerawatPendidikan;
use App\Models\PerawatPelatihan;
use App\Models\PerawatPekerjaan;
use App\Models\PerawatKeluarga;
use App\Models\PerawatOrganisasi;
use App\Models\PerawatTandaJasa;

class AdminPerawatController extends Controller
{
    // LIST SEMUA PERAWAT
    public function index()
    {
        $perawat = User::where('role', 'perawat')
            ->with('profile')
            ->orderBy('name')
            ->get();

        return view('admin.perawat.index', compact('perawat'));
    }

    // DETAIL DRH PER PERAWAT
    public function show($id)
    {
        $user = User::where('role', 'perawat')->findOrFail($id);

        $profile   = PerawatProfile::where('user_id', $user->id)->first();
        $pendidikan = PerawatPendidikan::where('user_id', $user->id)->get();
        $pelatihan  = PerawatPelatihan::where('user_id', $user->id)->get();
        $pekerjaan  = PerawatPekerjaan::where('user_id', $user->id)->get();
        $keluarga   = PerawatKeluarga::where('user_id', $user->id)->get();
        $organisasi = PerawatOrganisasi::where('user_id', $user->id)->get();
        $tandajasa  = PerawatTandaJasa::where('user_id', $user->id)->get();

        return view('admin.perawat.show', compact(
            'user',
            'profile',
            'pendidikan',
            'pelatihan',
            'pekerjaan',
            'keluarga',
            'organisasi',
            'tandajasa'
        ));
    }
}
