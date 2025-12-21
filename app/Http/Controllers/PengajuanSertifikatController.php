<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSertifikat;
use App\Models\PenanggungJawabUjian;
use App\Models\JadwalWawancara;
use App\Models\PerawatLisensi;
// Import Library Penting
use PhpOffice\PhpWord\TemplateProcessor; // Untuk Word
use Barryvdh\DomPDF\Facade\Pdf;          // Untuk PDF
use Carbon\Carbon;

class PengajuanSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $listSertifikat = PerawatLisensi::select('nama')->distinct()->pluck('nama');

        $query = PengajuanSertifikat::where('user_id', $user->id)
            ->with(['lisensiLama', 'jadwalWawancara.pewawancara']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lisensiLama', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sertifikat')) {
            $query->whereHas('lisensiLama', function ($q) use ($request) {
                $q->where('nama', $request->sertifikat);
            });
        }

        $pengajuan = $query->latest()->paginate(10)->withQueryString();
        $pjs = PenanggungJawabUjian::all();

        return view('perawat.pengajuan.index', compact('user', 'pengajuan', 'pjs', 'listSertifikat'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $lisensi = PerawatLisensi::findOrFail($request->lisensi_id);
        $metodeOtomatis = $lisensi->metode_perpanjangan ?? 'pg_only';

        $exists = PengajuanSertifikat::where('user_id', $user->id)
            ->where('lisensi_lama_id', $request->lisensi_id)
            ->whereIn('status', ['pending', 'method_selected', 'exam_passed', 'interview_scheduled'])
            ->exists();

        if ($exists) {
            return back()->with('swal', ['icon' => 'warning', 'title' => 'Ups', 'text' => 'Pengajuan untuk lisensi ini sedang diproses.']);
        }

        PengajuanSertifikat::create([
            'user_id' => $user->id,
            'lisensi_lama_id' => $request->lisensi_id,
            'status' => 'pending',
            'metode' => $metodeOtomatis
        ]);

        return redirect()->route('perawat.pengajuan.index')
            ->with('swal', ['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Permintaan dikirim. Metode evaluasi telah ditentukan sistem.']);
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

        return back()->with('swal', ['icon' => 'success', 'title' => 'Tersimpan', 'text' => 'Jadwal wawancara berhasil diajukan.']);
    }

    /**
     * FUNGSI PRINT SERTIFIKAT (WORD atau PDF)
     * Logika:
     * - Interview Only -> Word (.docx)
     * - PG / PG+Interview -> PDF (.pdf)
     */
    public function printSertifikat($id)
    {
        // 1. Ambil Data
        $user = Auth::user();
        $pengajuan = PengajuanSertifikat::where('user_id', $user->id)
            ->with(['user.perawatProfile', 'user.pendidikanTerakhir', 'lisensiLama'])
            ->findOrFail($id);

        if ($pengajuan->status != 'completed') {
            return back()->with('swal', ['icon' => 'error', 'title' => 'Gagal', 'text' => 'Sertifikat belum tersedia.']);
        }

        // Persiapan Data Umum
        $profile = $pengajuan->user->perawatProfile;
        $lisensi = $pengajuan->lisensiLama;
        $namaLengkap = $profile->nama_lengkap ?? $user->name;

        // Setup Tanggal (Indonesia)
        Carbon::setLocale('id');

        // Logika Tanggal Mulai/Ujian
        if (!empty($lisensi->tgl_mulai)) {
            $carbonDate = Carbon::parse($lisensi->tgl_mulai);
        } elseif (!empty($lisensi->tgl_diselenggarakan)) {
             $carbonDate = Carbon::parse($lisensi->tgl_diselenggarakan);
        } else {
            $carbonDate = Carbon::parse($pengajuan->updated_at);
        }

        $tglMulaiIndo = $carbonDate->translatedFormat('d F Y');
        $tglSelesaiIndo = $carbonDate->addYears(3)->translatedFormat('d F Y');
        $tglTerbitIndo = Carbon::now()->translatedFormat('d F Y'); // Tanggal cetak

        // ============================================================
        // LOGIKA PERCABANGAN (IF-ELSE) SESUAI METODE
        // ============================================================

        if ($pengajuan->metode == 'interview_only') {

            // --------------------------------------------------------
            // OPSI A: GENERATE WORD (.DOCX) - Untuk Metode Wawancara Saja
            // --------------------------------------------------------

            $pathTemplate = storage_path('app/templates/template_serkom.docx');
            if (!file_exists($pathTemplate)) {
                return back()->with('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Template Word tidak ditemukan.']);
            }

            $templateProcessor = new TemplateProcessor($pathTemplate);

            // Mapping Data Word
            $templateProcessor->setValue('nama', strtoupper($namaLengkap));
            $templateProcessor->setValue('nirp', $profile->nirp ?? '-');
            $templateProcessor->setValue('unit_kerja', strtoupper($user->unit_kerja ?? 'RSUD SLG'));
            $templateProcessor->setValue('bidang', strtoupper($lisensi->bidang ?? 'KEPERAWATAN'));
            $templateProcessor->setValue('kfk', strtoupper($lisensi->kfk ?? $lisensi->nama));

            $pendidikanData = $pengajuan->user->pendidikanTerakhir;
            $txtPendidikan = $pendidikanData ? trim($pendidikanData->jenjang . ' ' . $pendidikanData->jurusan) : '-';
            $templateProcessor->setValue('pendidikan', strtoupper($txtPendidikan));

            $templateProcessor->setValue('tgl_mulai', $tglMulaiIndo);
            $templateProcessor->setValue('tgl_selesai', $tglSelesaiIndo);

            // Simpan & Download Word
            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $namaLengkap);
            $fileName  = 'Sertifikat_Wawancara_' . $cleanName . '.docx';
            $tempPath  = storage_path('app/public/' . $fileName);
            $templateProcessor->saveAs($tempPath);

            return response()->download($tempPath)->deleteFileAfterSend(true);

        } else {

            // --------------------------------------------------------
            // OPSI B: GENERATE PDF (HTML) - Untuk Metode Ujian (PG) / Campuran
            // --------------------------------------------------------

            // Siapkan data untuk View Blade
            $dataPDF = [
                'user' => $user,
                'profile' => $profile,
                'lisensi' => $lisensi,
                'pengajuan' => $pengajuan,
                'tgl_ujian_indo' => $tglMulaiIndo,
                'tgl_expired_indo' => $tglSelesaiIndo,
                'tgl_terbit_indo' => $tglTerbitIndo
            ];

            // Render PDF
            $pdf = Pdf::loadView('perawat.pengajuan.sertifikat_pdf', $dataPDF);
            $pdf->setPaper('a4', 'landscape');

            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $namaLengkap);
            return $pdf->download('Sertifikat_Kompetensi_' . $cleanName . '.pdf');
        }
    }
}
