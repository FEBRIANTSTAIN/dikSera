@extends('layouts.app')

@section('title','Detail DRH Perawat')

@section('content')
<div class="dash-card p-3 mb-3">
    <h5 class="mb-1">{{ $user->name }}</h5>
    <div class="small text-muted mb-2">DRH Perorangan</div>

    <div class="row g-2 small">
        <div class="col-md-6">
            <strong>NIK:</strong> {{ $profile->nik ?? '—' }} <br>
            <strong>TTL:</strong> {{ $profile->tempat_lahir ?? '' }}, {{ $profile->tanggal_lahir ?? '' }} <br>
            <strong>Jenis Kelamin:</strong> {{ $profile->jenis_kelamin ?? '—' }} <br>
            <strong>Agama:</strong> {{ $profile->agama ?? '—' }} <br>
            <strong>Status:</strong> {{ $profile->status_perkawinan ?? '—' }} <br>
        </div>
        <div class="col-md-6">
            <strong>Alamat:</strong> {{ $profile->alamat ?? '—' }} <br>
            <strong>No HP:</strong> {{ $profile->no_hp ?? '—' }} <br>
            <strong>Tinggi / Berat:</strong> {{ $profile->tinggi_badan ?? '—' }} / {{ $profile->berat_badan ?? '—' }} <br>
            <strong>Gol. Darah:</strong> {{ $profile->golongan_darah ?? '—' }} <br>
        </div>
    </div>

    @if($profile->foto_3x4)
        <div class="mt-3">
            <img src="{{ asset('storage/'.$profile->foto_3x4) }}"
                 style="height:120px;border-radius:8px;border:1px solid #ddd;">
        </div>
    @endif
</div>

{{-- SECTION TABLES --}}
@include('admin.perawat._section', [
    'title' => 'Pendidikan',
    'data'  => $pendidikan,
    'cols' => ['jenjang','nama_institusi','tahun_lulus']
])

@include('admin.perawat._section', [
    'title' => 'Kursus / Pelatihan',
    'data'  => $pelatihan,
    'cols' => ['nama_pelatihan','penyelenggara','tahun']
])

@include('admin.perawat._section', [
    'title' => 'Riwayat Pekerjaan',
    'data'  => $pekerjaan,
    'cols' => ['nama_instansi','jabatan','tahun_mulai','tahun_selesai']
])

@include('admin.perawat._section', [
    'title' => 'Riwayat Keluarga',
    'data'  => $keluarga,
    'cols' => ['hubungan','nama','pekerjaan']
])

@include('admin.perawat._section', [
    'title' => 'Organisasi',
    'data'  => $organisasi,
    'cols' => ['nama_organisasi','jabatan','tahun_mulai','tahun_selesai']
])

@include('admin.perawat._section', [
    'title' => 'Tanda Jasa / Penghargaan',
    'data'  => $tandajasa,
    'cols' => ['nama_penghargaan','tahun']
])

@endsection
