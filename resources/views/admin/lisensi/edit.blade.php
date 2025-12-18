@extends('layouts.app')

@section('title', 'Edit Lisensi – DIKSERA')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
{{-- Load Choices CSS untuk User Select (Single) agar tampilan konsisten --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

<style>
    :root {
        --primary-blue: #2563eb;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --bg-light: #f8fafc;
        --input-border: #e2e8f0;
        --accent-orange: #f59e0b; /* Warna tema Edit */
        --accent-hover: #d97706;
        --primary-light: #eff6ff; /* Tambahan dari create */
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
    }

    /* --- Header --- */
    .page-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        font-size: 1.75rem; /* Samakan dengan create */
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        letter-spacing: -0.025em;
    }

    /* --- Form Card --- */
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03); /* Update shadow */
        padding: 40px;
    }

    /* --- Inputs --- */
    .form-label {
        font-size: 0.875rem; /* Samakan */
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: block;
    }

    .required-star {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control, .form-select {
        border: 1px solid var(--input-border);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: var(--text-dark);
        background-color: #fff;
        transition: all 0.2s ease;
        width: 100%;
    }

    /* Focus Orange untuk Edit */
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }

    /* Input Group (Icons) */
    .input-group-text {
        background-color: var(--bg-light);
        border: 1px solid var(--input-border);
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: var(--text-gray);
        padding-left: 16px; padding-right: 16px;
    }
    .input-group .form-control, .input-group .form-select {
        border-top-left-radius: 0; border-bottom-left-radius: 0;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--accent-orange);
        color: var(--accent-orange);
    }

    /* --- Choices JS Custom Style (Orange Theme) --- */
    .choices__inner {
        background-color: #fff;
        border: 1px solid var(--input-border);
        border-radius: 10px;
        min-height: 50px;
        display: flex; align-items: center;
    }
    .choices:focus-within .choices__inner {
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }
    .choices__list--single { padding: 0; }

    /* --- Buttons --- */
    .btn-submit-edit {
        background-color: var(--accent-orange);
        color: white;
        width: 100%;
        padding: 16px; /* Samakan padding */
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.25);
        transition: all 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .btn-submit-edit:hover {
        background-color: var(--accent-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .btn-back {
        background: white;
        border: 1px solid #e2e8f0;
        color: var(--text-gray);
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background-color: var(--bg-light);
        color: var(--text-dark);
        border-color: #cbd5e1;
    }

    /* Highlight untuk input metode (Callout Style) */
    .metode-wrapper {
        background-color: #fffbeb; /* Kuning muda (tema edit) */
        border: 1px solid #fde68a;
        border-left: 5px solid var(--accent-orange);
        border-radius: 8px;
        padding: 24px;
    }

    /* Helper text */
    .form-text { font-size: 0.85rem; color: var(--text-gray); margin-top: 5px; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8"> <div class="page-header">
                <div>
                    <h1 class="page-title">Edit Data Lisensi</h1>
                    <p class="page-subtitle" style="margin-top: 5px; font-size: 0.9rem; color: #64748b;">Perbarui informasi lisensi perawat</p>
                </div>
                <a href="{{ route('admin.lisensi.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <div class="form-card">
                @if($errors->any())
                    <div class="alert alert-danger py-3 px-4 mb-4" style="border-radius: 10px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
                    </div>
                @endif

                {{-- HAPUS enctype karena tidak ada upload file --}}
                <form action="{{ route('admin.lisensi.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Pilih Pemilik Lisensi --}}
                        <div class="col-12">
                            <label class="form-label">Pemilik Lisensi <span class="required-star">*</span></label>
                            {{-- Gunakan ID khusus untuk Choices JS --}}
                            <select name="user_id" id="choice-user-single" class="form-select @error('user_id') is-invalid @enderror" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $data->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Aturan Perpanjangan --}}
                        <div class="col-12">
                            <div class="metode-wrapper">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-sliders text-warning fs-4 mt-1"></i>
                                    <div class="w-100">
                                        <label class="form-label text-dark mb-1">Aturan Perpanjangan <span class="required-star">*</span></label>
                                        <p class="small text-muted mb-3">Update metode evaluasi untuk perpanjangan lisensi ini.</p>

                                        <select name="metode_perpanjangan" class="form-select border-warning fw-bold @error('metode_perpanjangan') is-invalid @enderror" required>
                                            <option value="pg_only" {{ old('metode_perpanjangan', $data->metode_perpanjangan) == 'pg_only' ? 'selected' : '' }}>
                                                Hanya Ujian Tulis (Pilihan Ganda)
                                            </option>
                                            <option value="pg_interview" {{ old('metode_perpanjangan', $data->metode_perpanjangan) == 'pg_interview' ? 'selected' : '' }}>
                                                Ujian Tulis + Wawancara
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25"></div>

                        {{-- Nama & Lembaga --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Lisensi <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $data->nama) }}" required placeholder="Contoh: STR, SIP">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lembaga Penerbit <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" name="lembaga" class="form-control @error('lembaga') is-invalid @enderror" value="{{ old('lembaga', $data->lembaga) }}" required placeholder="Contoh: Kemenkes RI">
                            </div>
                        </div>

                        {{-- Nomor (Tetap ada di Edit agar bisa koreksi jika auto-generate salah) --}}
                        <div class="col-12">
                            <label class="form-label">Nomor Lisensi <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror" value="{{ old('nomor', $data->nomor) }}" required>
                            </div>
                            <div class="form-text text-end">Nomor ini digenerate otomatis, namun dapat diedit jika perlu.</div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="tgl_terbit" class="form-control @error('tgl_terbit') is-invalid @enderror" value="{{ old('tgl_terbit', $data->tgl_terbit) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Expired <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                <input type="date" name="tgl_expired" class="form-control @error('tgl_expired') is-invalid @enderror" value="{{ old('tgl_expired', $data->tgl_expired) }}" required>
                            </div>
                        </div>

                        {{-- Upload File DIHAPUS (Sesuai Create) --}}

                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn-submit-edit">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Load JS Choices --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Choices untuk Single Select
            const element = document.getElementById('choice-user-single');
            const choices = new Choices(element, {
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Cari pemilik lisensi...',
                noResultsText: 'Tidak ada perawat ditemukan',
                itemSelectText: 'Tekan untuk memilih',
                shouldSort: false,
            });
        });
    </script>
@endpush
