@extends('layouts.app')

@section('title', 'Tambah Lisensi – DIKSERA')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- 1. Load CSS Choices --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        /* ... (CSS SAMA SEPERTI SEBELUMNYA) ... */
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fafc;
            --input-border: #e2e8f0;
            --input-bg: #ffffff;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-top: 5px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--input-border);
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .input-group-text {
            background-color: var(--bg-light);
            border: 1px solid var(--input-border);
            border-right: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            color: var(--text-gray);
            padding-left: 16px;
            padding-right: 16px;
        }

        .input-group .form-control,
        .input-group .form-select {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .choices__inner {
            background-color: #fff;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        .choices:focus-within .choices__inner {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .choices__input {
            background-color: transparent;
            margin-bottom: 0;
        }

        .choices__list--multiple .choices__item {
            background-color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            border-radius: 6px;
        }

        .btn-submit {
            background-color: var(--primary-blue);
            color: white;
            width: 100%;
            padding: 16px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-back {
            background: white;
            border: 1px solid var(--input-border);
            color: var(--text-gray);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .metode-wrapper {
            background-color: var(--primary-light);
            border: 1px solid #dbeafe;
            border-left: 5px solid var(--primary-blue);
            border-radius: 8px;
            padding: 24px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">

                {{-- Header --}}
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Tambah Lisensi Baru</h1>
                        <p class="page-subtitle">Formulir administrasi data lisensi perawat</p>
                    </div>
                    <a href="{{ route('admin.lisensi.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                {{-- Form Card --}}
                <div class="form-card">
                    @if ($errors->any())
                        <div class="alert alert-danger py-3 px-4 mb-4"
                            style="border-radius: 10px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.lisensi.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">

                            {{-- Pilih Perawat (Multi Select) --}}
                            <div class="col-12">
                                <label class="form-label">Pilih Perawat (Bisa Banyak) <span
                                        class="required-star">*</span></label>
                                <div class="mb-1">
                                    <select name="user_ids[]" id="choice-users" class="form-select" multiple required>
                                        <option value="">Cari Nama Perawat...</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ collect(old('user_ids'))->contains($user->id) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text">Nomor lisensi akan digenerate otomatis berurutan untuk setiap perawat
                                    yang dipilih.</div>
                            </div>

                            {{-- Aturan Perpanjangan --}}
                            <div class="col-12">
                                <div class="metode-wrapper">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="bi bi-sliders text-primary fs-4 mt-1"></i>
                                        <div class="w-100">
                                            <label class="form-label text-primary mb-1">Aturan Perpanjangan <span
                                                    class="required-star">*</span></label>
                                            <p class="small text-muted mb-3">Tentukan metode evaluasi otomatis untuk
                                                perpanjangan lisensi ini.</p>

                                            <select name="metode_perpanjangan" class="form-select border-primary" required>
                                                <option value="pg_only"
                                                    {{ old('metode_perpanjangan') == 'pg_only' ? 'selected' : '' }}>
                                                    Hanya Ujian Tulis (Pilihan Ganda)
                                                </option>
                                                <option value="pg_interview"
                                                    {{ old('metode_perpanjangan') == 'pg_interview' ? 'selected' : '' }}>
                                                    Ujian Tulis + Wawancara
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-25">
                            </div>

                            {{-- Nama & Lembaga --}}
                            <div class="col-md-6">
                                <label class="form-label">Nama Lisensi <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                        placeholder="Contoh: STR, SIP" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lembaga Penerbit <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" name="lembaga" class="form-control" value="{{ old('lembaga') }}"
                                        placeholder="Contoh: Kemenkes RI" required>
                                </div>
                            </div>

                            {{-- NOMOR LISENSI DIHAPUS (AUTO GENERATE) --}}

                            {{-- Tanggal --}}
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Terbit <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                    <input type="date" name="tgl_terbit" class="form-control"
                                        value="{{ old('tgl_terbit') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Expired <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                    <input type="date" name="tgl_expired" class="form-control"
                                        value="{{ old('tgl_expired') }}" required>
                                </div>
                            </div>

                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-save2"></i> Simpan Data Lisensi
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
            const element = document.getElementById('choice-users');
            const choices = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Cari dan pilih perawat...',
                noResultsText: 'Tidak ada perawat ditemukan',
                itemSelectText: 'Tekan untuk memilih',
            });
        });
    </script>
@endpush
