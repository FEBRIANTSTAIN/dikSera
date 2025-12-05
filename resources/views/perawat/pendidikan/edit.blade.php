@extends('layouts.app')

@section('title', 'Edit Pendidikan – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dash-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 fw-bold text-warning">Edit Data Pendidikan</h6>
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

                <form action="{{ route('perawat.pendidikan.update', $pendidikan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jenjang <span class="text-danger">*</span></label>
                            <input type="text" name="jenjang" class="form-control" value="{{ old('jenjang', $pendidikan->jenjang) }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Institusi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_institusi" class="form-control" value="{{ old('nama_institusi', $pendidikan->nama_institusi) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $pendidikan->jurusan) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tempat / Kota</label>
                            <input type="text" name="tempat" class="form-control" value="{{ old('tempat', $pendidikan->tempat) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Akreditasi</label>
                            <input type="text" name="akreditasi" class="form-control" value="{{ old('akreditasi', $pendidikan->akreditasi) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="{{ old('tahun_masuk', $pendidikan->tahun_masuk) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" class="form-control" value="{{ old('tahun_lulus', $pendidikan->tahun_lulus) }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Upload Ijazah</label>
                            <input type="file" name="dokumen" class="form-control">
                            @if($pendidikan->dokumen_path)
                                <div class="mt-2 small">
                                    <span class="text-muted">File saat ini:</span>
                                    <a href="{{ asset('storage/'.$pendidikan->dokumen_path) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat Ijazah
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-warning text-dark px-4">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
