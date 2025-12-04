<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerawatProfile;
use App\Models\PerawatPendidikan;
use App\Models\PerawatPelatihan;
use App\Models\PerawatPekerjaan;
use App\Models\PerawatTandaJasa;
use App\Models\PerawatKeluarga;
use App\Models\PerawatOrganisasi;

class DashboardController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('swal', [
                'icon'  => 'warning',
                'title' => 'Silakan login terlebih dahulu',
                'text'  => 'Anda harus login untuk mengakses dashboard.',
            ]);
        }

        $user = Auth::user();

        // profil perawat (DRH I – keterangan perorangan)
        $profile = PerawatProfile::where('user_id', $user->id)->first();

        // hitung data DRH multi
        $pendidikanCount = PerawatPendidikan::where('user_id', $user->id)->count();
        $pelatihanCount  = PerawatPelatihan::where('user_id', $user->id)->count();
        $pekerjaanCount  = PerawatPekerjaan::where('user_id', $user->id)->count();
        $tandaJasaCount  = PerawatTandaJasa::where('user_id', $user->id)->count();
        $keluargaCount   = PerawatKeluarga::where('user_id', $user->id)->count();
        $organisasiCount = PerawatOrganisasi::where('user_id', $user->id)->count();

        // cek kelengkapan per bagian
        $sections = [
            'identitas'  => $profile && $profile->nik && $profile->tanggal_lahir && $profile->no_hp,
            'alamat'     => $profile && $profile->alamat,
            'badan'      => $profile && $profile->tinggi_badan && $profile->berat_badan,
            'pendidikan' => $pendidikanCount > 0,
            'pelatihan'  => $pelatihanCount > 0,
            'pekerjaan'  => $pekerjaanCount > 0,
            'keluarga'   => $keluargaCount > 0,
            'organisasi' => $organisasiCount > 0,
            'tandajasa'  => $tandaJasaCount > 0,
        ];

        $totalSections   = count($sections);
        $completed       = count(array_filter($sections));
        $progressPercent = $totalSections > 0 ? round(($completed / $totalSections) * 100) : 0;

        // siapkan list status untuk tabel
        $statusList = [
            [
                'nama'   => 'Keterangan Perorangan',
                'kode'   => 'identitas',
                'status' => $sections['identitas'],
            ],
            [
                'nama'   => 'Alamat Lengkap',
                'kode'   => 'alamat',
                'status' => $sections['alamat'],
            ],
            [
                'nama'   => 'Keterangan Badan',
                'kode'   => 'badan',
                'status' => $sections['badan'],
            ],
            [
                'nama'   => 'Pendidikan',
                'kode'   => 'pendidikan',
                'status' => $sections['pendidikan'],
                'jumlah' => $pendidikanCount,
            ],
            [
                'nama'   => 'Kursus / Pelatihan',
                'kode'   => 'pelatihan',
                'status' => $sections['pelatihan'],
                'jumlah' => $pelatihanCount,
            ],
            [
                'nama'   => 'Riwayat Pekerjaan',
                'kode'   => 'pekerjaan',
                'status' => $sections['pekerjaan'],
                'jumlah' => $pekerjaanCount,
            ],
            [
                'nama'   => 'Riwayat Keluarga',
                'kode'   => 'keluarga',
                'status' => $sections['keluarga'],
                'jumlah' => $keluargaCount,
            ],
            [
                'nama'   => 'Organisasi',
                'kode'   => 'organisasi',
                'status' => $sections['organisasi'],
                'jumlah' => $organisasiCount,
            ],
            [
                'nama'   => 'Tanda Jasa / Penghargaan',
                'kode'   => 'tandajasa',
                'status' => $sections['tandajasa'],
                'jumlah' => $tandaJasaCount,
            ],
        ];

        // kalau belum lengkap, kirim swal info sekali
        if ($progressPercent < 100 && ! session()->has('swal')) {
            session()->flash('swal', [
                'icon'  => 'info',
                'title' => 'DRH Anda belum lengkap',
                'text'  => 'Lengkapi semua bagian DRH agar proses kompetensi & sertifikasi lebih lancar.',
            ]);
        }

        return view('dashboard.index', compact(
            'user',
            'profile',
            'progressPercent',
            'completed',
            'totalSections',
            'statusList'
        ));
    }
}
