@extends('layouts.app')

@section('title','Edit Identitas – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="dash-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Edit Identitas & DRH Perorangan</h6>
            <a href="{{ route('perawat.drh') }}" class="btn btn-sm btn-outline-secondary">
                Kembali ke DRH
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perawat.identitas.update') }}" method="POST" enctype="multipart/form-data" class="small">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    {{-- Nama, NIK --}}
                    <div class="mb-2">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap"
                               value="{{ old('nama_lengkap',$profile->nama_lengkap ?? $user->name) }}"
                               class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik"
                               value="{{ old('nik',$profile->nik ?? '') }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir"
                                   value="{{ old('tempat_lahir',$profile->tempat_lahir ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir',$profile->tanggal_lahir ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select form-select-sm">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin',$profile->jenis_kelamin ?? '')=='L'?'selected':'' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin',$profile->jenis_kelamin ?? '')=='P'?'selected':'' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama"
                                   value="{{ old('agama',$profile->agama ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Status Perkawinan</label>
                        <input type="text" name="status_perkawinan"
                               value="{{ old('status_perkawinan',$profile->status_perkawinan ?? '') }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">No HP (WA)</label>
                        <input type="text" name="no_hp"
                               value="{{ old('no_hp',$profile->no_hp ?? '') }}"
                               class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="2" class="form-control form-control-sm">{{ old('alamat',$profile->alamat ?? '') }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota"
                               value="{{ old('kota',$profile->kota ?? '') }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label">Tinggi (cm)</label>
                            <input type="number" name="tinggi_badan"
                                   value="{{ old('tinggi_badan',$profile->tinggi_badan ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="berat_badan"
                                   value="{{ old('berat_badan',$profile->berat_badan ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Gol. Darah</label>
                            <input type="text" name="golongan_darah"
                                   value="{{ old('golongan_darah',$profile->golongan_darah ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                   value="{{ old('jabatan',$profile->jabatan ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Pangkat</label>
                            <input type="text" name="pangkat"
                                   value="{{ old('pangkat',$profile->pangkat ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Golongan</label>
                            <input type="text" name="golongan"
                                   value="{{ old('golongan',$profile->golongan ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip"
                                   value="{{ old('nip',$profile->nip ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label">NIRP</label>
                            <input type="text" name="nirp"
                                   value="{{ old('nirp',$profile->nirp ?? '') }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Foto 3x4</label>
                        <input type="file" name="foto_3x4" class="form-control form-control-sm">
                        @if($profile && $profile->foto_3x4)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$profile->foto_3x4) }}"
                                     style="height:110px;border-radius:8px;border:1px solid #e5e7eb;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
