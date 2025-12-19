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

        if (!$pewawancara) {
            abort(403);
        }

        // ===== LIST PRIORITAS =====
        $antrian = JadwalWawancara::with(['pengajuan.user'])
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'approved')
            ->orderByRaw("
            CASE
                WHEN DATE(waktu_wawancara) < CURDATE() THEN 1
                WHEN DATE(waktu_wawancara) = CURDATE() THEN 2
                ELSE 3
            END
        ")
            ->orderBy('waktu_wawancara', 'asc')
            ->get();

        // ===== KALENDER =====
        $events = $antrian->map(fn($j) => [
            'title' => $j->pengajuan->user->name,
            'start' => $j->waktu_wawancara->toIso8601String(),
            'url'   => route('pewawancara.penilaian', $j->id)
        ]);

        // ===== RIWAYAT SINGKAT =====
        $riwayat = JadwalWawancara::with('pengajuan.user')
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->where('status', 'completed')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.pewawancara', compact(
            'pewawancara',
            'antrian',
            'events',
            'riwayat'
        ));
    }

    public function quickStore(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $request->validate([
            'kompetensi' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'pengetahuan' => 'required|integer|min:0|max:100',
            'keputusan' => 'required|in:lulus,tidak_lulus',
        ]);

        DB::transaction(function () use ($request, $jadwal) {
            WawancaraPenilaian::create([
                'jadwal_wawancara_id' => $jadwal->id,
                'skor_kompetensi' => $request->kompetensi,
                'skor_sikap' => $request->sikap,
                'skor_pengetahuan' => $request->pengetahuan,
                'keputusan' => $request->keputusan,
            ]);

            $jadwal->update(['status' => 'completed']);
        });

        return response()->json(['success' => true]);
    }


    // MEMPERBAIKI ERROR undefined variable pewawancara
    public function showPenilaian($id)
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        $jadwal = JadwalWawancara::with(['pengajuan.user', 'pengajuan.lisensiLama'])
            ->where('penanggung_jawab_id', $pewawancara->id) // Security check: Pastikan milik pewawancara ini
            ->findOrFail($id);

        if ($jadwal->status !== 'approved') {
            return redirect()->route('pewawancara.dashboard')
                ->with('error', 'Sesi wawancara ini sudah selesai atau tidak valid.');
        }

        return view('pewawancara.penilaian', compact('jadwal', 'pewawancara'));
    }

    public function storePenilaian(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $request->validate([
            'skor_kompetensi' => 'required|integer|min:0|max:100',
            'skor_sikap' => 'required|integer|min:0|max:100',
            'skor_pengetahuan' => 'required|integer|min:0|max:100',
            'keputusan' => 'required|in:lulus,tidak_lulus',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            WawancaraPenilaian::create([
                'jadwal_wawancara_id' => $jadwal->id,
                'skor_kompetensi' => $request->skor_kompetensi,
                'skor_sikap' => $request->skor_sikap,
                'skor_pengetahuan' => $request->skor_pengetahuan,
                'catatan_pewawancara' => $request->catatan,
                'keputusan' => $request->keputusan
            ]);

            $jadwal->update(['status' => 'completed']);

            if ($request->keputusan == 'lulus') {
                $jadwal->pengajuan->update(['status' => 'completed']);

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
            return redirect()->route('pewawancara.dashboard')->with('success', 'Penilaian disimpan. Tugas selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        $user = Auth::user();
        $pewawancara = $user->penanggungJawab;

        $riwayat = JadwalWawancara::with(['pengajuan.user', 'penilaian', 'pengajuan.lisensiLama'])
            ->where('penanggung_jawab_id', $pewawancara->id)
            ->whereIn('status', ['completed', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pewawancara.riwayat', compact('riwayat'));
    }
}
