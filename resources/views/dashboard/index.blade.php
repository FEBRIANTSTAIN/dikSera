@extends('layouts.app')

@section('title', 'Dashboard Perawat – DIKSERA')

@section('content')
<div class="container-fluid py-3">
    {{-- HERO --}}
    <div class="row g-3 align-items-stretch">
        <div class="col-lg-8">
            <div class="dash-card h-100 d-flex flex-column flex-md-row align-items-center p-3">
                <div class="hero-icon-wrapper me-md-3 mb-3 mb-md-0">
                    <div class="hero-icon-circle">
                        <img src="{{ asset('icon.png') }}" alt="DIKSERA" class="hero-icon-img">
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h1 class="dash-title mb-1">Hai, {{ $user->name ?? 'Perawat' }} 👋</h1>
                    <p class="dash-subtitle mb-2">
                        Selamat datang di <strong>DIKSERA</strong><br>
                        Digitalisasi Kompetensi, Sertifikasi &amp; Evaluasi Perawat.
                    </p>
                    <div class="small text-muted">
                        Lengkapi <strong>Daftar Riwayat Hidup (DRH)</strong>, kompetensi, dan dokumen pendukungmu di sini.
                    </div>
                </div>
            </div>
        </div>

        {{-- PROGRESS --}}
        <div class="col-lg-4">
            <div class="dash-card h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Progres Kelengkapan DRH</span>
                        <span class="badge bg-primary-subtle text-primary fw-semibold">
                            {{ $progressPercent }}%
                        </span>
                    </div>
                    <div class="progress glass-progress mb-2">
                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $progressPercent }}%;"
                             aria-valuenow="{{ $progressPercent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <div class="small text-muted">
                        {{ $completed }} dari {{ $totalSections }} bagian DRH telah terisi.
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('register.perawat') }}"
                       class="btn btn-sm btn-primary w-100">
                        Lengkapi / Perbarui DRH
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK INFO + PROFILE --}}
    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <div class="dash-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Ringkasan Profil</h6>
                    <span class="badge bg-light text-primary border">
                        DRH Perorangan
                    </span>
                </div>
                <div class="small">
                    <div class="mb-1">
                        <span class="label">Nama</span>
                        <span class="value">{{ $profile->nama_lengkap ?? $user->name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="label">NIK</span>
                        <span class="value">{{ $profile->nik ?? '—' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="label">Tanggal Lahir</span>
                        <span class="value">
                            {{ $profile && $profile->tanggal_lahir ? $profile->tanggal_lahir : '—' }}
                        </span>
                    </div>
                    <div class="mb-1">
                        <span class="label">No. HP (WA)</span>
                        <span class="value">{{ $profile->no_hp ?? '—' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="label">Alamat</span>
                        <span class="value">
                            {{ $profile->alamat ?? 'Belum diisi' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD INFO KOMPETENSI/ SERTIF (placeholder modul selanjutnya) --}}
        <div class="col-md-6">
            <div class="dash-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Kompetensi &amp; Sertifikasi</h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        Coming next – Modul 4 & 5
                    </span>
                </div>
                <p class="small mb-2 text-muted">
                    Di modul berikutnya, bagian ini akan menampilkan:
                </p>
                <ul class="small mb-1 ps-3">
                    <li>Kompetensi klinis &amp; non klinis yang dimiliki</li>
                    <li>Masa berlaku STR, sertifikat pelatihan, dan lainnya</li>
                    <li>Reminder jadwal perpanjangan via Telegram</li>
                </ul>
                <p class="small text-muted mb-0">
                    Sementara ini, fokus dulu melengkapi seluruh data DRH ya nande 💙
                </p>
            </div>
        </div>
    </div>

    {{-- TABLE STATUS DRH --}}
    <div class="dash-card mt-3 p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Status Kelengkapan DRH</h6>
            <span class="small text-muted">Ikuti urutan dari atas agar rapi.</span>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Bagian</th>
                        <th style="width:140px;" class="text-center">Status</th>
                        <th style="width:120px;" class="text-center">Jumlah Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusList as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td class="text-center">
                                @if($row['status'])
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Sudah diisi
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        Belum lengkap
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($row['jumlah']))
                                    {{ $row['jumlah'] }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="small text-muted mt-2">
            * Status otomatis dihitung dari data yang sudah Anda isi di form registrasi / DRH.
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dash-card{
        border-radius:18px;
        background:rgba(255,255,255,0.96);
        border:1px solid rgba(209,213,219,0.8);
        box-shadow:0 18px 40px rgba(15,23,42,0.09);
        backdrop-filter:blur(10px);
    }
    .dash-title{
        font-size:18px;
        font-weight:600;
        color:#0f172a;
    }
    .dash-subtitle{
        font-size:13px;
        color:#6b7280;
    }
    .hero-icon-wrapper{
        flex-shrink:0;
    }
    .hero-icon-circle{
        width:82px;
        height:82px;
        border-radius:26px;
        background:radial-gradient(circle at 20% 0,#eff6ff,#1d4ed8);
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow:0 18px 40px rgba(37,99,235,0.45);
        overflow:hidden;
    }
    .hero-icon-img{
        width:100%;
        height:100%;
        object-fit:contain;
    }
    .glass-progress{
        background:rgba(226,232,255,0.9);
        border-radius:999px;
        height:10px;
    }
    .glass-progress .progress-bar{
        background:linear-gradient(135deg,#2563eb,#60a5fa);
        border-radius:999px;
    }
    .label{
        display:inline-block;
        width:110px;
        color:#6b7280;
    }
    .value{
        font-weight:500;
        color:#111827;
    }

    @media (max-width: 767.98px){
        .dash-title{
            font-size:16px;
        }
        .hero-icon-circle{
            width:70px;
            height:70px;
        }
    }
</style>
@endpush
