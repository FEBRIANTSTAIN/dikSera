@extends('layouts.app')

@section('title', 'Riwayat Wawancara')

@push('styles')
    <style>
        /* --- Root Variables (Konsisten) --- */
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f1f5f9;
            --blue-soft: #eff6ff;
            --blue-border: #dbeafe;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
        }

        /* --- Page Header --- */
        .page-header {
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        /* --- Card & Table --- */
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: var(--text-gray);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-custom td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .table-custom tr:hover td {
            background-color: #fcfcfc;
        }

        /* --- Components --- */
        .avatar-sm {
            width: 36px;
            height: 36px;
            background-color: var(--blue-soft);
            color: var(--primary-blue);
            border: 1px solid var(--blue-border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .bg-pass {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .bg-fail {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .btn-detail {
            color: var(--primary-blue);
            background: var(--blue-soft);
            border: 1px solid var(--blue-border);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-detail:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-1px);
        }

        .btn-back {
            color: var(--text-gray);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .btn-back:hover {
            background-color: white;
            color: var(--primary-blue);
        }

        /* --- Modal Styling (Scorecard) --- */
        .modal-content {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .score-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            height: 100%;
        }

        .score-val {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1;
            margin-bottom: 4px;
        }

        .score-lbl {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-gray);
            font-weight: 600;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .info-row span:first-child {
            color: var(--text-gray);
        }

        .info-row span:last-child {
            font-weight: 600;
            color: var(--text-dark);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4 px-4">

        {{-- Header --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Riwayat Wawancara</h2>
                <p class="text-muted mb-0 small">Arsip penilaian yang telah Anda selesaikan.</p>
            </div>
            <a href="{{ route('pewawancara.dashboard') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali Dashboard
            </a>
        </div>

        {{-- Card Table --}}
        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Peserta</th>
                                <th width="25%">Waktu Pelaksanaan</th>
                                <th width="20%">Keputusan</th>
                                <th width="15%" class="text-end">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $index => $jadwal)
                                <tr>
                                    <td class="text-center text-muted">{{ $riwayat->firstItem() + $index }}</td>

                                    {{-- Kolom Peserta --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $name = $jadwal->pengajuan->user->name;
                                                $initials = collect(explode(' ', $name))
                                                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                                    ->take(2)
                                                    ->join('');
                                            @endphp
                                            <div class="avatar-sm">{{ $initials }}</div>
                                            <div class="d-flex flex-column" style="line-height: 1.2;">
                                                <span class="fw-bold text-dark">{{ $name }}</span>
                                                <span class="text-muted small" style="font-size: 0.8rem;">
                                                    {{ $jadwal->pengajuan->user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Waktu --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fw-medium text-dark">{{ $jadwal->waktu_wawancara->format('d M Y') }}</span>
                                            <span class="text-muted small">Pukul
                                                {{ $jadwal->waktu_wawancara->format('H:i') }} WIB</span>
                                        </div>
                                    </td>

                                    {{-- Kolom Keputusan --}}
                                    <td>
                                        @if ($jadwal->penilaian)
                                            @if ($jadwal->penilaian->keputusan == 'lulus')
                                                <span class="badge-status bg-pass">
                                                    <i class="bi bi-check-circle-fill"></i> LULUS
                                                </span>
                                            @else
                                                <span class="badge-status bg-fail">
                                                    <i class="bi bi-x-circle-fill"></i> TIDAK LULUS
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="text-end">
                                        <button class="btn btn-detail" data-bs-toggle="modal"
                                            data-bs-target="#modalDetail{{ $jadwal->id }}">
                                            Lihat Nilai
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL DETAIL (Per Row) --}}
                                <div class="modal fade" id="modalDetail{{ $jadwal->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Detail Penilaian</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- Info Ringkas --}}
                                                <div class="mb-4 pb-3 border-bottom">
                                                    <div class="info-row">
                                                        <span>Nama Peserta</span>
                                                        <span>{{ $jadwal->pengajuan->user->name }}</span>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Waktu Wawancara</span>
                                                        <span>{{ $jadwal->waktu_wawancara->format('d F Y, H:i') }}</span>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Lokasi</span>
                                                        <span>{{ $jadwal->lokasi ?? 'Online/RSUD' }}</span>
                                                    </div>
                                                </div>

                                                @if ($jadwal->penilaian)
                                                    {{-- Score Cards --}}
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-4">
                                                            <div class="score-box">
                                                                <div class="score-val text-primary">
                                                                    {{ $jadwal->penilaian->skor_kompetensi }}</div>
                                                                <div class="score-lbl">Kompetensi</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="score-box">
                                                                <div class="score-val text-info">
                                                                    {{ $jadwal->penilaian->skor_sikap }}</div>
                                                                <div class="score-lbl">Sikap</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="score-box">
                                                                <div class="score-val text-warning">
                                                                    {{ $jadwal->penilaian->skor_pengetahuan }}</div>
                                                                <div class="score-lbl">Pengetahuan</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Catatan --}}
                                                    <div class="bg-light p-3 rounded-3 border">
                                                        <label class="small text-muted fw-bold text-uppercase mb-1">Catatan
                                                            Pewawancara</label>
                                                        <p class="mb-0 text-dark small" style="font-style: italic;">
                                                            "{{ $jadwal->penilaian->catatan_pewawancara ?? 'Tidak ada catatan khusus.' }}"
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="text-center py-3 text-muted">Data penilaian tidak ditemukan.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                                                <button type="button" class="btn btn-light border px-4"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- End Modal --}}
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center opacity-50">
                                            <i class="bi bi-clock-history display-4 text-muted mb-3"></i>
                                            <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat</h6>
                                            <p class="small text-muted mb-0">Anda belum melakukan penilaian apapun.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($riwayat->hasPages())
                <div class="mt-4"> {{ $riwayat->withQueryString()->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </div>
@endsection
