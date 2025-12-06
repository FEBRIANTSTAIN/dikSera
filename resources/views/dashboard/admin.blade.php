@extends('layouts.app')

@section('title', 'Dashboard Admin – DIKSERA')

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
                    <h1 class="dash-title mb-1">Halo Admin 👋</h1>
                    <p class="dash-subtitle mb-2">
                        Selamat datang di <strong>DIKSERA</strong><br>
                        Panel kendali untuk monitoring, validasi, dan manajemen data perawat.
                    </p>
                    <div class="small text-muted">
                        Kelola data, verifikasi DRH, pantau aktifitas pengguna, dan modifikasi sistem di sini.
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD METRIK UTAMA --}}
        <div class="col-lg-4">
            <div class="dash-card h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Total Perawat Terdaftar</span>
                        <span class="badge bg-primary-subtle text-primary fw-semibold">
                            {{-- {{ $totalPerawat }} --}}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Menunggu Verifikasi DRH</span>
                        <span class="badge bg-warning-subtle text-warning fw-semibold">
                            {{-- {{ $pendingVerifikasi }} --}}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Total Pengguna Sistem</span>
                        <span class="badge bg-success-subtle text-success fw-semibold">
                            {{-- {{ $totalUsers }} --}}
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.perawat.index') }}"
                   class="btn btn-sm btn-primary w-100 mt-3">
                    Kelola Data Perawat
                </a>
            </div>
        </div>
    </div>

    {{-- ROW RINGKASAN SISTEM --}}
    <div class="row g-3 mt-1">

        {{-- RINGKASAN VERIFIKASI --}}
        <div class="col-md-6">
            <div class="dash-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Ringkasan Verifikasi DRH</h6>
                    <span class="badge bg-light text-primary border">
                        Monitoring
                    </span>
                </div>

                <div class="small">
                    <div class="mb-1 d-flex justify-content-between">
                        <span class="label">Belum Diperiksa</span>
                        {{-- <span class="value">{{ $pendingVerifikasi }}</span> --}}
                    </div>

                    <div class="mb-1 d-flex justify-content-between">
                        <span class="label">Sedang Diproses</span>
                        {{-- <span class="value">{{ $onProgress }}</span> --}}
                    </div>

                    <div class="mb-1 d-flex justify-content-between">
                        <span class="label">Selesai Diverifikasi</span>
                        {{-- <span class="value">{{ $verified }}</span> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- LOG & AKTIVITAS PENGGUNA --}}
        <div class="col-md-6">
            <div class="dash-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Aktivitas Terbaru</h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        20 Log Terakhir
                    </span>
                </div>

                <ul class="small mb-0 ps-3">
                    {{-- @forelse($recentActivities as $act)
                        <li class="mb-1">
                            <strong>{{ $act->user->name }}</strong> —
                            {{ $act->description }}
                            <br>
                            <span class="text-muted">{{ $act->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="text-muted">Belum ada aktivitas.</li>
                    @endforelse --}}
                </ul>
            </div>
        </div>
    </div>

    {{-- TABLE STATUS MODUL --}}
    <div class="dash-card mt-3 p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Status Modul & Fitur</h6>
            <span class="small text-muted">Pantau progres implementasi modul DIKSERA.</span>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Modul</th>
                        <th class="text-center" style="width:140px;">Status</th>
                        <th class="text-center" style="width:120px;">Keterangan</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach($modulesStatus as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td class="text-center">
                                @if($row['status'] === 'ready')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Siap
                                    </span>
                                @elseif($row['status'] === 'progress')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        Proses
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        Coming soon
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $row['catatan'] ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody> --}}
            </table>
        </div>

        <div class="small text-muted mt-2">
            * Data ini dapat diatur dari konfigurasi admin.
        </div>
    </div>

</div>
@endsection

@push('styles')
{{-- seluruh style tetap sama seperti dashboard perawat --}}
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
    .hero-icon-circle{
        width:82px;height:82px;border-radius:26px;
        background:radial-gradient(circle at 20% 0,#eff6ff,#1d4ed8);
        display:flex;align-items:center;justify-content:center;
        box-shadow:0 18px 40px rgba(37,99,235,0.45);
        overflow:hidden;
    }
    .hero-icon-img{width:100%;height:100%;object-fit:contain;}
    .label{display:inline-block;width:150px;color:#6b7280;}
    .value{font-weight:500;color:#111827;}

    @media (max-width: 767.98px){
        .dash-title{font-size:16px;}
        .hero-icon-circle{width:70px;height:70px;}
    }
</style>
@endpush
