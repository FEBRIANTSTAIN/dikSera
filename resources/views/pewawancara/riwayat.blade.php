@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Riwayat Penilaian</h2>
            <p class="text-muted">Daftar peserta yang telah selesai Anda wawancarai.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Tanggal Wawancara</th>
                            <th>Nama Peserta</th>
                            <th>Lisensi</th>
                            <th class="text-center">Skor (K/S/P)</th>
                            <th class="text-center">Keputusan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                        <tr>
                            <td>{{ $item->waktu_wawancara->format('d M Y, H:i') }}</td>
                            <td class="fw-bold">{{ $item->pengajuan->user->name }}</td>
                            <td>{{ $item->pengajuan->lisensiLama->nama ?? 'Pengajuan Baru' }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    {{ $item->penilaian->skor_kompetensi }} / 
                                    {{ $item->penilaian->skor_sikap }} / 
                                    {{ $item->penilaian->skor_pengetahuan }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->penilaian->keputusan == 'lulus')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">LULUS</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">TIDAK LULUS</span>
                                @endif
                            </td>
                            <td class="text-muted small fst-italic">
                                {{ Str::limit($item->penilaian->catatan_pewawancara, 50) ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat penilaian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
@endsection