@extends('layouts.app')

@section('title', 'Tambah Keluarga – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dash-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Tambah Anggota Keluarga</h6>
                    <a href="{{ route('perawat.keluarga.index') }}" class="btn btn-sm btn-outline-secondary">
                        Kembali
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('perawat.keluarga.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Hubungan *</label>
                        <select name="hubungan" class="form-select" required>
                            <option value="">- Pilih Hubungan -</option>
                            <option value="Suami" {{ old('hubungan') == 'Suami' ? 'selected' : '' }}>Suami</option>
                            <option value="Istri" {{ old('hubungan') == 'Istri' ? 'selected' : '' }}>Istri</option>
                            <option value="Anak" {{ old('hubungan') == 'Anak' ? 'selected' : '' }}>Anak</option>
                            <option value="Ayah" {{ old('hubungan') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ old('hubungan') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Saudara" {{ old('hubungan') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                            <option value="Mertua" {{ old('hubungan') == 'Mertua' ? 'selected' : '' }}>Mertua</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
