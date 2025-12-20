@extends('layouts.app')

@section('title', 'Antrian Wawancara')

@push('styles')
    <style>
        /* --- 1. ROOT VARIABLES (UPDATED) --- */
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f1f5f9;

            /* Additional Helper Colors based on Primary Blue */
            --blue-soft: #eff6ff;
            --blue-border: #dbeafe;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            /* Pastikan font sesuai layout utama */
        }

        /* --- 2. Page Header --- */
        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }

        /* --- 3. Card Styling --- */
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* --- 4. Table Styling --- */
        .table-custom th {
            background-color: #f8fafc;
            color: var(--text-gray);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
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
            background-color: #f8fafc;
            /* Hover effect halus */
        }

        /* --- 5. Components --- */

        /* Avatar Initials */
        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: var(--blue-soft);
            color: var(--primary-blue);
            border: 1px solid var(--blue-border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Date Box */
        .date-box {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .date-main {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .date-sub {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* Soft Badges */
        .badge-soft {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .bs-warning {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fcd34d;
        }

        .bs-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .bs-info {
            background: var(--blue-soft);
            color: var(--primary-blue);
            border: 1px solid var(--blue-border);
        }

        /* Buttons */
        .btn-action {
            background-color: white;
            color: var(--primary-blue);
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-action:hover {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4 px-4">

        {{-- HEADER SECTION --}}
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="page-title">Antrian Wawancara</h2>
                <p class="text-muted mb-0 small">Daftar peserta yang menunggu giliran penilaian Anda.</p>
            </div>
            <a href="{{ route('pewawancara.dashboard') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        {{-- MAIN CONTENT CARD --}}
        <div class="card card-custom">

            {{-- Table Wrapper --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th width="20%">Jadwal</th>
                                <th width="30%">Identitas Peserta</th>
                                <th width="20%">Status</th>
                                <th width="20%">Lisensi</th>
                                <th width="10%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antrian as $jadwal)
                                <tr>
                                    {{-- Kolom Waktu --}}
                                    <td>
                                        <div class="date-box">
                                            <span class="date-main">{{ $jadwal->waktu_wawancara->format('d M Y') }}</span>
                                            <div class="d-flex align-items-center gap-1 date-sub">
                                                <i class="bi bi-clock"></i> {{ $jadwal->waktu_wawancara->format('H:i') }}
                                                WIB
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Peserta (Avatar + Nama) --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                // Generate Initials
                                                $name = $jadwal->pengajuan->user->name;
                                                $initials = collect(explode(' ', $name))
                                                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                                    ->take(2)
                                                    ->join('');
                                            @endphp

                                            <div class="avatar-circle">{{ $initials }}</div>

                                            <div class="d-flex flex-column" style="line-height: 1.2;">
                                                <span class="fw-bold text-dark"
                                                    style="font-size: 0.95rem;">{{ $name }}</span>
                                                <span class="text-muted small" style="font-size: 0.8rem;">
                                                    {{ $jadwal->pengajuan->user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Status Badge --}}
                                    <td>
                                        @if ($jadwal->waktu_wawancara->isToday())
                                            <span class="badge badge-soft bs-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i> Hari Ini
                                            </span>
                                        @elseif($jadwal->waktu_wawancara->lt(now()))
                                            <span class="badge badge-soft bs-danger">
                                                <i class="bi bi-x-circle me-1"></i> Terlewat
                                            </span>
                                        @else
                                            <span class="badge badge-soft bs-info">
                                                <i class="bi bi-calendar-event me-1"></i> Akan Datang
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Kolom Lisensi --}}
                                    <td>
                                        <div class="d-flex flex-column" style="line-height: 1.2;">
                                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $jadwal->pengajuan->lisensiLama->nama ?? 'Pengajuan Baru' }}
                                            </span>
                                            <span class="text-muted small" style="font-size: 0.75rem;">
                                                ID: #{{ $jadwal->pengajuan->id }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="text-end">
                                        <a href="{{ route('pewawancara.penilaian', $jadwal->id) }}" class="btn-action">
                                            <i class="bi bi-pencil-square"></i> Nilai
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center opacity-50">
                                            <div class="bg-light rounded-circle p-3 mb-3">
                                                <i class="bi bi-calendar-check text-muted" style="font-size: 2.5rem;"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Tidak Ada Antrian</h6>
                                            <p class="small text-muted mb-0">Saat ini tidak ada peserta yang perlu dinilai.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($antrian->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $antrian->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
