@extends('layouts.app')

@section('title', 'Riwayat Pendidikan – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="dash-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-0 fw-bold">Riwayat Pendidikan</h6>
                <small class="text-muted">Kelola data pendidikan formal dan akademik Anda.</small>
            </div>
            <div>
                <a href="{{ route('perawat.drh') }}" class="btn btn-sm btn-outline-secondary me-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('perawat.pendidikan.create') }}" class="btn btn-sm btn-primary">
                    + Tambah Pendidikan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABEL LIST --}}
        <div class="table-responsive small">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:5%;" class="text-center">No</th>
                        <th style="width:25%;">Jenjang & Institusi</th>
                        <th style="width:20%;">Jurusan</th>
                        <th style="width:15%;">Tempat & Akreditasi</th>
                        <th style="width:15%;">Tahun (Masuk - Lulus)</th>
                        <th style="width:10%;">Dokumen</th>
                        <th style="width:10%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendidikan as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ $row->jenjang }}</div>
                                <div class="fw-bold text-dark">{{ $row->nama_institusi }}</div>
                            </td>
                            <td>{{ $row->jurusan }}</td>
                            <td>
                                <div>{{ $row->tempat }}</div>
                                <div class="text-muted small">Akreditasi: {{ $row->akreditasi ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $row->tahun_masuk }} - {{ $row->tahun_lulus }}
                            </td>
                            <td class="text-center">
                                @if($row->dokumen_path)
                                    <a href="{{ asset('storage/'.$row->dokumen_path) }}" target="_blank" class="btn btn-sm btn-light border" title="Lihat Ijazah">
                                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('perawat.pendidikan.edit', $row->id) }}" class="btn btn-sm btn-warning text-dark">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('perawat.pendidikan.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data pendidikan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <div class="mb-2"><i class="bi bi-mortarboard display-6 opacity-25"></i></div>
                                Belum ada data pendidikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
