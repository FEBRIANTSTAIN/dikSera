@extends('layouts.app')

@section('title', 'Edit Keluarga – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dash-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Edit Anggota Keluarga</h6>
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

                {{-- Perhatikan rute update membutuhkan ID --}}
                <form action="{{ route('perawat.keluarga.update', $keluarga->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Hubungan *</label>
                        <select name="hubungan" class="form-select" required>
                            <option value="">- Pilih Hubungan -</option>
                            @php $hub = old('hubungan', $keluarga->hubungan); @endphp
                            <option value="Suami" {{ $hub == 'Suami' ? 'selected' : '' }}>Suami</option>
                            <option value="Istri" {{ $hub == 'Istri' ? 'selected' : '' }}>Istri</option>
                            <option value="Anak" {{ $hub == 'Anak' ? 'selected' : '' }}>Anak</option>
                            <option value="Ayah" {{ $hub == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ $hub == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Saudara" {{ $hub == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                            <option value="Mertua" {{ $hub == 'Mertua' ? 'selected' : '' }}>Mertua</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $keluarga->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $keluarga->tanggal_lahir) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $keluarga->pekerjaan) }}">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
