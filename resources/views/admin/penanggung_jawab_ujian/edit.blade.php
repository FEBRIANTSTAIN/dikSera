@extends('layouts.app')

@section('title', 'Edit Penanggung Jawab – Admin DIKSERA')

@push('styles')
    <style>
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft, #e2e8f0);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 32px;
        }

        .form-control-custom {
            border-radius: 8px;
            border: 1px solid var(--border-soft, #e2e8f0);
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control-custom:focus {
            border-color: var(--blue-main, #0d6efd);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        /* Tambahan agar select terlihat rapi */
        select.form-control-custom {
            appearance: auto;
            cursor: pointer;
        }
    </style>
@endpush


@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8"> {{-- Sedikit diperlebar agar muat 2 kolom form --}}

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.penanggung-jawab.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                    style="border-radius: 8px;">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('admin.penanggung-jawab.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5 class="mb-4 fw-bold text-dark border-bottom pb-3">Edit Penanggung Jawab</h5>

                        {{-- SECTION 1: DATA AKUN LOGIN --}}
                        <div class="alert alert-light border-start border-4 border-primary shadow-sm mb-4">
                            <small class="fw-bold d-block mb-1 text-primary"><i class="bi bi-shield-lock me-1"></i>
                                Informasi Login Akun</small>
                            <span class="text-muted small">Email dan Password ini digunakan penanggung jawab untuk login ke
                                dashboard.</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-muted text-uppercase fw-bold">Email Login <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    {{-- Mengambil email dari relasi User --}}
                                    <input type="email" name="email"
                                        value="{{ old('email', $item->user->email ?? '') }}"
                                        class="form-control border-start-0 ps-0" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-muted text-uppercase fw-bold">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0"
                                        placeholder="Isi hanya jika ingin ganti password">
                                </div>
                                <small class="text-muted" style="font-size: 10px;">*Kosongkan jika password tidak
                                    diubah.</small>
                            </div>
                        </div>

                        <hr class="text-muted my-2 opacity-25">

                        {{-- SECTION 2: DATA PROFIL --}}
                        <div class="mt-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-lines-fill me-2"></i>Data Profil</h6>

                            <div class="mb-4">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"
                                        style="border-color: #e2e8f0;"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                                        class="form-control form-control-custom border-start-0 ps-0" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Tipe Penanggung Jawab <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"
                                            style="border-color: #e2e8f0;"><i class="bi bi-person-badge"></i></span>
                                        <select name="type" class="form-control form-control-custom border-start-0 ps-0"
                                            required>
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="pewawancara"
                                                {{ old('type', $item->type) == 'pewawancara' ? 'selected' : '' }}>
                                                Pewawancara
                                            </option>
                                            <option value="ujian"
                                                {{ old('type', $item->type) == 'ujian' ? 'selected' : '' }}>
                                                Pengawas Ujian
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"
                                            style="border-color: #e2e8f0;"><i class="bi bi-briefcase"></i></span>
                                        <input type="text" name="jabatan" value="{{ old('jabatan', $item->jabatan) }}"
                                            class="form-control form-control-custom border-start-0 ps-0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">No. WhatsApp / HP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"
                                        style="border-color: #e2e8f0;"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="no_hp" value="{{ old('no_hp', $item->no_hp) }}"
                                        class="form-control form-control-custom border-start-0 ps-0" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                style="border-radius: 8px; background-color: #0d6efd; border-color: #0d6efd;">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
