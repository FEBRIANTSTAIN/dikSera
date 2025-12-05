@extends('layouts.app')

@section('title', 'Data Keluarga – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="dash-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Data Keluarga</h6>
            <div>
                <a href="{{ route('perawat.drh') }}" class="btn btn-sm btn-outline-secondary me-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                {{-- Tombol Menuju Halaman Create --}}
                <a href="{{ route('perawat.keluarga.create') }}" class="btn btn-sm btn-primary">
                    + Tambah Keluarga
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABEL LIST --}}
        <div class="table-responsive small">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:15%;">Hubungan</th>
                        <th>Nama Lengkap</th>
                        <th style="width:15%;">Tanggal Lahir</th>
                        <th style="width:20%;">Pekerjaan</th>
                        <th style="width:15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keluarga as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td>{{ $row->hubungan }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->tanggal_lahir }}</td>
                            <td>{{ $row->pekerjaan }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Tombol ke Halaman Edit --}}
                                    <a href="{{ route('perawat.keluarga.edit', $row->id) }}" class="btn btn-sm btn-warning text-white">
                                        Edit
                                    </a>

                                    {{-- Form Delete --}}
                                    <form action="{{ route('perawat.keluarga.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data keluarga ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Belum ada data keluarga.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
