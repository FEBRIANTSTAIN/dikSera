<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalWawancara;
use App\Models\WawancaraPenilaian;

class AdminPengajuanWawancaraController extends Controller
{
    public function approveJadwal($id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);
        $jadwal->update(['status' => 'approved']);
        return back()->with('success', 'Pengajuan jadwal wawancara disetujui.');
    }

    public function rejectJadwal(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);
        $jadwal->update([
            'status' => 'rejected',
            'catatan_admin' => $request->alasan
        ]);

        $jadwal->pengajuan->update(['status' => 'exam_passed']);

        return back()->with('success', 'Pengajuan jadwal ditolak/direvisi.');
    }

    public function showPenilaian($id)
    {
        $jadwal = JadwalWawancara::with(['pengajuan.user', 'pewawancara'])->findOrFail($id);
        return view('admin.pengajuan_wawancara.penilaian', compact('jadwal'));
    }

    public function storePenilaian(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $request->validate([
            'skor_kompetensi' => 'required|integer|min:0|max:100',
            'skor_sikap' => 'required|integer|min:0|max:100',
            'skor_pengetahuan' => 'required|integer|min:0|max:100',
            'keputusan' => 'required|in:lulus,tidak_lulus'
        ]);

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
            $lisensi = $jadwal->pengajuan->lisensiLama;
            if ($lisensi) {
                $lisensi->update([
                    'tgl_terbit'  => now(),
                    'tgl_expired' => now()->addYears(3)
                ]);
            }
        } else {
            $jadwal->pengajuan->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.pengajuan.index')
            ->with('success', 'Penilaian disimpan. Status perawat dan lisensi telah diperbarui.');
    }
}
