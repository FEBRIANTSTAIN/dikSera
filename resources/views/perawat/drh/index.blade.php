@extends('layouts.app')

@section('title','DRH Perawat – DIKSERA')

@section('content')
<div class="container-fluid py-3">

    <div class="row g-3">
        {{-- PROFIL RINGKAS --}}
        <div class="col-lg-6">
            <div class="dash-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Profil & DRH Perorangan</h6>
                    <a href="{{ route('perawat.identitas.edit') }}" class="btn btn-sm btn-primary">
                        Edit Identitas
                    </a>
                </div>

                <div class="row g-2 small">
                    <div class="col-md-6">
                        <div><span class="text-muted">Nama</span><br>
                            <strong>{{ $profile->nama_lengkap ?? $user->name }}</strong>
                        </div>
                        <div class="mt-1"><span class="text-muted">NIK</span><br>
                            {{ $profile->nik ?? '—' }}
                        </div>
                        <div class="mt-1"><span class="text-muted">TTL</span><br>
                            {{ $profile->tempat_lahir ?? '—' }},
                            {{ $profile->tanggal_lahir ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div><span class="text-muted">No HP (WA)</span><br>
                            {{ $profile->no_hp ?? '—' }}
                        </div>
                        <div class="mt-1"><span class="text-muted">Alamat</span><br>
                            {{ $profile->alamat ?? 'Belum diisi' }}
                        </div>
                        <div class="mt-1"><span class="text-muted">Gol. Darah</span><br>
                            {{ $profile->golongan_darah ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOTO & INFO KECIL --}}
        <div class="col-lg-6">
            <div class="dash-card p-3 h-100 d-flex align-items-center">
                <div class="me-3">
                    <div class="hero-icon-circle" style="width:76px;height:76px;">
                        <img src="{{ asset('icon.png') }}" class="hero-icon-img" alt="DIKSERA">
                    </div>
                </div>
                <div class="small">
                    <div class="mb-1">
                        <strong>DIKSERA – Digitalisasi Kompetensi, Sertifikasi & Evaluasi Perawat</strong>
                    </div>
                    <div class="text-muted">
                        Lengkapi semua bagian DRH, lalu nanti di modul berikutnya
                        sistem akan menarik data ini ke laman kompetensi & sertifikasi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RINGKASAN PENDIDIKAN --}}
    <div class="dash-card p-3 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Pendidikan</h6>
            <a href="{{ route('perawat.pendidikan.index') }}" class="btn btn-sm btn-outline-primary">
                Kelola Pendidikan
            </a>
        </div>

        @if($pendidikan->isEmpty())
            <div class="small text-muted">
                Belum ada data pendidikan. Silakan tambahkan minimal satu riwayat pendidikan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Jenjang</th>
                            <th>Nama Institusi</th>
                            <th>Akreditasi</th>
                            <th>Tempat</th>
                            <th>Tahun Lulus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendidikan as $i => $row)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $row->jenjang }}</td>
                                <td>{{ $row->nama_institusi }}</td>
                                <td>{{ $row->akreditasi ?? '—' }}</td>
                                <td>{{ $row->tempat ?? '—' }}</td>
                                <td>{{ $row->tahun_lulus ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

@push('styles')
<style>
    .dash-card{
        border-radius:18px;
        background:rgba(255,255,255,0.96);
        border:1px solid rgba(209,213,219,0.8);
        box-shadow:0 18px 40px rgba(15,23,42,0.08);
        backdrop-filter:blur(10px);
    }
    .hero-icon-circle{
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
</style>
@endpush
