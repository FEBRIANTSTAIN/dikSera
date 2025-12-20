@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('dashboard.pewawancara') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h2>Riwayat Wawancara</h2>
                <p class="text-muted">Pewawancara: {{ $pewawancara->nama }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama Peserta</th>
                                <th width="15%">Waktu Wawancara</th>
                                <th width="15%">Status</th>
                                <th width="15%">Keputusan</th>
                                <th width="15%">Rata-rata Skor</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $index => $jadwal)
                                <tr>
                                    <td>{{ $riwayat->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $jadwal->pengajuan->user->name }}</strong><br>
                                        <small class="text-muted">{{ $jadwal->pengajuan->user->email }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar"></i> {{ $jadwal->waktu_wawancara->format('d M Y') }}<br>
                                        <i class="fas fa-clock"></i> {{ $jadwal->waktu_wawancara->format('H:i') }}
                                    </td>
                                    <td>
                                        @if ($jadwal->status == 'completed')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($jadwal->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($jadwal->penilaian)
                                            @if ($jadwal->penilaian->keputusan == 'lulus')
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> LULUS
                                                </span>
                                            @else
                                                <span class="badge badge-danger badge-lg">
                                                    <i class="fas fa-times-circle"></i> TIDAK LULUS
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($jadwal->penilaian)
                                            @php
                                                $avg =
                                                    ($jadwal->penilaian->skor_kompetensi +
                                                        $jadwal->penilaian->skor_sikap +
                                                        $jadwal->penilaian->skor_pengetahuan) /
                                                    3;
                                            @endphp
                                            <strong>{{ number_format($avg, 1) }}</strong>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-{{ $avg >= 75 ? 'success' : ($avg >= 60 ? 'warning' : 'danger') }}"
                                                    style="width: {{ $avg }}%"></div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#detailModal{{ $jadwal->id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $jadwal->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Detail Penilaian Wawancara</h5>
                                                <button type="button" class="close text-white"
                                                    data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6><strong>Data Peserta</strong></h6>
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td><strong>{{ $jadwal->pengajuan->user->name }}</strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>{{ $jadwal->pengajuan->user->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Waktu</td>
                                                                <td>{{ $jadwal->waktu_wawancara->format('d M Y, H:i') }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tempat</td>
                                                                <td>{{ $jadwal->tempat_wawancara }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if ($jadwal->penilaian)
                                                            <h6><strong>Hasil Penilaian</strong></h6>
                                                            <table class="table table-sm">
                                                                <tr>
                                                                    <td>Kompetensi</td>
                                                                    <td><strong>{{ $jadwal->penilaian->skor_kompetensi }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Sikap</td>
                                                                    <td><strong>{{ $jadwal->penilaian->skor_sikap }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Pengetahuan</td>
                                                                    <td><strong>{{ $jadwal->penilaian->skor_pengetahuan }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Keputusan</td>
                                                                    <td>
                                                                        @if ($jadwal->penilaian->keputusan == 'lulus')
                                                                            <span class="badge badge-success">LULUS</span>
                                                                        @else
                                                                            <span class="badge badge-danger">TIDAK
                                                                                LULUS</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($jadwal->penilaian && $jadwal->penilaian->catatan_pewawancara)
                                                    <hr>
                                                    <h6><strong>Catatan Pewawancara</strong></h6>
                                                    <p class="text-muted">{{ $jadwal->penilaian->catatan_pewawancara }}</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada riwayat wawancara.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $riwayat->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
