@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Hasil Ujian</h4>
                <p class="text-muted mb-0">Form: <strong>{{ $form->judul }}</strong></p>
            </div>
            <a href="{{ route('admin.form.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Statistik Ringkas (Opsional, biar keren) --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="small opacity-75">Total Peserta</div>
                        <div class="fs-4 fw-bold">{{ $results->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="small text-muted">Rata-rata Nilai</div>
                        <div class="fs-4 fw-bold text-dark">
                            {{ $results->count() > 0 ? round($results->avg('total_nilai'), 1) : 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="small text-muted">Nilai Tertinggi</div>
                        <div class="fs-4 fw-bold text-success">
                            {{ $results->max('total_nilai') ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="small text-muted">Nilai Terendah</div>
                        <div class="fs-4 fw-bold text-danger">
                            {{ $results->min('total_nilai') ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Hasil --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Peserta</th>
                                <th>Waktu Selesai</th>
                                <th class="text-center">B/S</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $res)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $res->user->name }}</div>
                                        <small class="text-muted">{{ $res->user->email }}</small>
                                    </td>
                                    <td>
                                        {{ $res->waktu_selesai ? \Carbon\Carbon::parse($res->waktu_selesai)->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold">{{ $res->total_benar }}</span> /
                                        <span class="text-danger fw-bold">{{ $res->total_salah }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                                            {{ $res->total_nilai }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($res->total_nilai >= 70)
                                            {{-- KKM Dummy 70 --}}
                                            <span class="badge bg-success bg-opacity-10 text-success">Lulus</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Remedial</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.form.reset-hasil', $res->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin mereset hasil user ini? User harus mengerjakan ulang dari awal.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Reset / Hapus Hasil">
                                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        Belum ada peserta yang mengerjakan ujian ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
