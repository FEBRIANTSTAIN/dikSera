@extends('layouts.app')

@section('title', 'Tambah Pendidikan – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dash-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 fw-bold text-primary">+ Tambah Data Pendidikan</h6>
                    <a href="{{ route('perawat.pendidikan.index') }}" class="btn btn-sm btn-outline-secondary">
                        Kembali
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('perawat.pendidikan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jenjang <span class="text-danger">*</span></label>
                            <input type="text" name="jenjang" class="form-control" value="{{ old('jenjang') }}" placeholder="Contoh: S1 / D3" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Institusi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_institusi" class="form-control" value="{{ old('nama_institusi') }}" placeholder="Nama Universitas / Sekolah" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}" placeholder="Ilmu Keperawatan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tempat / Kota</label>
                            <input type="text" name="tempat" class="form-control" value="{{ old('tempat') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Akreditasi</label>
                            <input type="text" name="akreditasi" class="form-control" value="{{ old('akreditasi') }}" placeholder="A / B / Unggul">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="{{ old('tahun_masuk') }}" placeholder="YYYY">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" class="form-control" value="{{ old('tahun_lulus') }}" placeholder="YYYY">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Upload Ijazah (PDF/Image)</label>
                            <input type="file" name="dokumen" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
