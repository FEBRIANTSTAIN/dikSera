<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalWawancara;

class AdminPengajuanWawancaraController extends Controller
{
    // Fungsi ini mentrigger jadwal muncul di dashboard pewawancara
    public function approveJadwal($id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        // Pastikan pewawancara sudah di-set (jika logic bisnis mengharuskannya)
        if (!$jadwal->penanggung_jawab_id) {
            return back()->with('error', 'Pewawancara belum ditentukan untuk jadwal ini.');
        }

        // Ubah status jadi 'approved' agar muncul di query PewawancaraController
        $jadwal->update(['status' => 'approved']);

        return back()->with('success', 'Jadwal disetujui. Data telah diteruskan ke Dashboard Pewawancara.');
    }

    public function rejectJadwal(Request $request, $id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        $jadwal->update([
            'status' => 'rejected', // Atau status lain sesuai alur (misal: reschedule)
            'catatan_admin' => $request->alasan
        ]);

        // Kembalikan status pengajuan agar user bisa mengajukan ulang/revisi
        $jadwal->pengajuan->update(['status' => 'exam_passed']);

        return back()->with('success', 'Pengajuan jadwal ditolak/dikembalikan ke peserta.');
    }
}
