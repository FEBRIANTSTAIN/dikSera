<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSertifikat;
use App\Models\PenanggungJawabUjian;
use App\Models\JadwalWawancara;
use App\Models\PerawatLisensi; // [PENTING] Jangan lupa import ini
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Ambil List Nama Sertifikat untuk Dropdown Filter
        $listSertifikat = PerawatLisensi::select('nama')->distinct()->pluck('nama');

        // 2. Query Dasar
        $query = PengajuanSertifikat::where('user_id', $user->id)
                                    ->with(['lisensiLama', 'jadwalWawancara.pewawancara']);

        // 3. Logika Filter Search (Nama Lisensi / Nomor)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lisensiLama', function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nomor', 'LIKE', "%{$search}%");
            });
        }

        // 4. Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Logika Filter Jenis Sertifikat
        if ($request->filled('sertifikat')) {
            $query->whereHas('lisensiLama', function($q) use ($request) {
                $q->where('nama', $request->sertifikat);
            });
        }

        // 6. Eksekusi dengan Pagination (agar {{ $pengajuan->links() }} jalan)
        $pengajuan = $query->latest()->paginate(10)->withQueryString();

        $pjs = PenanggungJawabUjian::all();

        // Kirim variabel $listSertifikat ke View
        return view('perawat.pengajuan.index', compact('user', 'pengajuan', 'pjs', 'listSertifikat'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $lisensi = \App\Models\PerawatLisensi::findOrFail($request->lisensi_id);

        // Default metode jika null
        $metodeOtomatis = $lisensi->metode_perpanjangan ?? 'pg_only';

        // Cek duplikasi pengajuan yang sedang berjalan
        $exists = PengajuanSertifikat::where('user_id', $user->id)
                    ->where('lisensi_lama_id', $request->lisensi_id)
                    ->whereIn('status', ['pending', 'method_selected', 'exam_passed', 'interview_scheduled'])
                    ->exists();

        if ($exists) {
            return back()->with('swal', ['icon'=>'warning', 'title'=>'Ups', 'text'=>'Pengajuan untuk lisensi ini sedang diproses.']);
        }

        PengajuanSertifikat::create([
            'user_id' => $user->id,
            'lisensi_lama_id' => $request->lisensi_id,
            'status' => 'pending',
            'metode' => $metodeOtomatis
        ]);

        return redirect()->route('perawat.pengajuan.index')
            ->with('swal', ['icon'=>'success', 'title'=>'Berhasil', 'text'=>'Permintaan dikirim. Metode evaluasi telah ditentukan sistem.']);
    }

    public function storeWawancara(Request $request, $id)
    {
        $pengajuan = PengajuanSertifikat::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'penanggung_jawab_id' => 'required',
            'tgl_wawancara' => 'required|date',
            'jam_wawancara' => 'required',
            'lokasi_wawancara' => 'required'
        ]);

        $waktu = $request->tgl_wawancara . ' ' . $request->jam_wawancara;

        JadwalWawancara::create([
            'pengajuan_sertifikat_id' => $pengajuan->id,
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'waktu_wawancara' => $waktu,
            'lokasi' => $request->lokasi_wawancara,
            'status' => 'pending'
        ]);

        $pengajuan->update(['status' => 'interview_scheduled']);

        return back()->with('swal', ['icon'=>'success', 'title'=>'Tersimpan', 'text'=>'Jadwal wawancara berhasil diajukan.']);
    }

    public function printSertifikat($id)
    {
        $user = Auth::user();
        $pengajuan = PengajuanSertifikat::where('user_id', $user->id)
                        ->with(['user', 'lisensiLama', 'jadwalWawancara.penilaian'])
                        ->findOrFail($id);

        if ($pengajuan->status != 'completed') {
            abort(403, 'Sertifikat belum tersedia.');
        }

        $pdf = Pdf::loadView('perawat.pengajuan.sertifikat_pdf', compact('pengajuan'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_Kompetensi_' . $user->name . '.pdf');
    }
}
