@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Antrian Wawancara</h2>
            <a href="{{ route('pewawancara.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th>Status Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrian as $jadwal)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $jadwal->waktu_wawancara->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $jadwal->waktu_wawancara->format('H:i') }} WIB</small>
                                    </td>
                                    <td>{{ $jadwal->pengajuan->user->name }}</td>
                                    <td>{{ $jadwal->pengajuan->user->email }}</td>
                                    <td>
                                        @if ($jadwal->waktu_wawancara->isToday())
                                            <span class="badge bg-warning text-dark">Hari Ini</span>
                                        @elseif($jadwal->waktu_wawancara->lt(now()))
                                            <span class="badge bg-danger">Terlewat</span>
                                        @else
                                            <span class="badge bg-info text-dark">Akan Datang</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pewawancara.penilaian', $jadwal->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Nilai Sekarang
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-check fa-2x mb-2"></i><br>
                                        Tidak ada antrian wawancara saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $antrian->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
