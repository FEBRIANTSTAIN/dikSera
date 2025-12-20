<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalWawancara;
use App\Models\WawancaraPenilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PewawancaraController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        if (!$pewawancara) abort(403);

        // Ambil data untuk statistik
        $totalAntrian = JadwalWawancara::where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'approved')->count();

        $hariIni = JadwalWawancara::where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'approved')
            ->whereDate('waktu_wawancara', now())->count();

        $selesai = JadwalWawancara::where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'completed')->count();

        // Data untuk Kalender (Hanya ID dan Tanggal)
        $jadwalKalender = JadwalWawancara::with('pengajuan.user')
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'approved')
            ->get()
            ->map(fn($j) => [
                'title' => $j->pengajuan->user->name,
                'start' => $j->waktu_wawancara->toIso8601String(),
                'url'   => route('pewawancara.penilaian', $j->id) // Link ke penilaian
            ]);

        return view('dashboard.pewawancara', compact('pewawancara', 'totalAntrian', 'hariIni', 'selesai', 'jadwalKalender'));
    }

    // Halaman Khusus List Antrian
    public function antrian()
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        $antrian = JadwalWawancara::with(['pengajuan.user'])
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'approved')
            ->orderBy('waktu_wawancara', 'asc')
            ->paginate(10); // Pakai pagination biar rapi

        return view('pewawancara.antrian', compact('antrian'));
    }

    public function showPenilaian($id)
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        $jadwal = JadwalWawancara::with(['pengajuan.user', 'pengajuan.lisensiLama'])->findOrFail($id);

        // Validasi: Hanya bisa menilai jika status approved
        if ($jadwal->status !== 'approved') {
            // FIX: Route name diperbaiki
            return redirect()->route('dashboard.pewawancara')
                ->with('error', 'Sesi wawancara tidak valid atau sudah selesai.');
        }

        return view('pewawancara.penilaian', compact('jadwal'));
    }

    public function storePenilaian(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $request->validate([
            'skor_kompetensi'  => 'required|integer|min:0|max:100',
            'skor_sikap'       => 'required|integer|min:0|max:100',
            'skor_pengetahuan' => 'required|integer|min:0|max:100',
            'keputusan'        => 'required|in:lulus,tidak_lulus',
            'catatan'          => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            WawancaraPenilaian::create([
                'jadwal_wawancara_id' => $jadwal->id,
                'skor_kompetensi'     => $request->skor_kompetensi,
                'skor_sikap'          => $request->skor_sikap,
                'skor_pengetahuan'    => $request->skor_pengetahuan,
                'catatan_pewawancara' => $request->catatan,
                'keputusan'           => $request->keputusan
            ]);

            // Update status jadwal menjadi selesai
            $jadwal->update(['status' => 'completed']);

            // Update status pengajuan utama
            if ($request->keputusan == 'lulus') {
                $jadwal->pengajuan->update(['status' => 'completed']);

                // Jika perpanjangan lisensi, update tanggal
                if ($jadwal->pengajuan->lisensiLama) {
                    $jadwal->pengajuan->lisensiLama->update([
                        'tgl_terbit'  => now(),
                        'tgl_expired' => now()->addYears(3)
                    ]);
                }
            } else {
                $jadwal->pengajuan->update(['status' => 'rejected']);
            }

            DB::commit();
            // FIX: Route name diperbaiki sesuai web.php
            return redirect()->route('pewawancara.antrian')->with('success', 'Penilaian disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        $riwayat = JadwalWawancara::with(['pengajuan.user', 'penilaian'])
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->whereIn('status', ['completed', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pewawancara.riwayat', compact('riwayat', 'pewawancara'));
    }
}
