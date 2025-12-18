@extends('layouts.app')

@php
    $pageTitle = 'Tambah Soal Baru';
    $pageSubtitle = 'Buat pertanyaan baru dan tentukan kunci jawabannya.';
@endphp

@section('title', 'Tambah Soal – Admin DIKSERA')

@push('styles')
    <style>
        /* Global Card */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 32px;
        }

        /* Form Controls */
        .form-control-custom,
        .form-select-custom {
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: var(--blue-main);
            box-shadow: 0 0 0 3px var(--blue-soft);
        }

        .form-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        /* Option Item Styling */
        .option-item {
            background: #f8fafc;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 10px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .option-item:focus-within {
            background: #fff;
            border-color: var(--blue-main);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Letter Box (A, B, C...) */
        .letter-box {
            width: 36px;
            height: 36px;
            background: #e2e8f0;
            color: #475569;
            font-weight: 700;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Radio Button Custom for Key Answer */
        .key-check-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            padding-left: 12px;
            border-left: 1px solid var(--border-soft);
            white-space: nowrap;
        }

        .form-check-input:checked {
            background-color: #198754;
            /* Green for correct answer */
            border-color: #198754;
        }

        /* Highlight row if it is the key */
        .option-item.is-key {
            border-color: #198754;
            background-color: #f0fdf4;
        }

        .option-item.is-key .letter-box {
            background-color: #198754;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Tombol Kembali --}}
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.bank-soal.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                    style="border-radius: 8px;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            <form action="{{ route('admin.bank-soal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="content-card">
                    <h5 class="mb-4 fw-bold text-dark border-bottom pb-3">Buat Soal Baru</h5>

                    {{-- Pertanyaan --}}
                    <div class="mb-4">
                        <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea name="pertanyaan" class="form-control form-control-custom" rows="4"
                            placeholder="Tuliskan pertanyaan disini..." required>{{ old('pertanyaan') }}</textarea>
                        {{-- Di bawah textarea pertanyaan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Gambar Soal (Opsional)</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            @if (isset($soal) && $soal->gambar)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Preview"
                                        style="max-height: 150px;" class="rounded border">
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label class="form-label">Kategori Soal <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select form-select-custom">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach (['Umum', 'Gak Umum'] as $kategori)
                                <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-light my-4">

                    {{-- Opsi Jawaban --}}
                    <div class="mb-2">
                        <label class="form-label mb-2">Opsi Jawaban & Kunci <span class="text-danger">*</span></label>
                        <p class="text-muted small mb-3">Isi teks jawaban dan tandai radio button di sebelah kanan untuk
                            jawaban yang <strong>Benar</strong>.</p>

                        <div class="d-flex flex-column gap-3">
                            @foreach (['a', 'b', 'c', 'd', 'e'] as $key)
                                <div class="option-item" id="option-row-{{ $key }}">
                                    {{-- Huruf --}}
                                    <div class="letter-box">{{ strtoupper($key) }}</div>

                                    {{-- Input Teks --}}
                                    <div class="flex-grow-1">
                                        <input type="text" name="opsi[{{ $key }}]"
                                            class="form-control border-0 bg-transparent p-0 shadow-none"
                                            placeholder="Tulis jawaban opsi {{ strtoupper($key) }}..."
                                            value="{{ old('opsi.' . $key) }}" required style="font-size: 14px;">
                                    </div>

                                    {{-- Radio Kunci Jawaban --}}
                                    <div class="key-check-wrapper">
                                        <input class="form-check-input key-radio" type="radio" name="kunci_jawaban"
                                            value="{{ $key }}" id="kunci_{{ $key }}" required
                                            {{ old('kunci_jawaban') == $key ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold cursor-pointer"
                                            for="kunci_{{ $key }}">
                                            Benar
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-light px-4" style="border-radius: 8px;">Reset</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                            <i class="bi bi-plus-lg me-1"></i> Simpan Soal
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Script untuk Highlight Row saat Radio dipilih --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const radios = document.querySelectorAll('.key-radio');
            const rows = document.querySelectorAll('.option-item');

            function updateHighlight() {
                // Reset semua row
                rows.forEach(row => {
                    row.classList.remove('is-key');
                });

                // Highlight row yang dipilih
                const checkedRadio = document.querySelector('.key-radio:checked');
                if (checkedRadio) {
                    const parentRow = checkedRadio.closest('.option-item');
                    if (parentRow) {
                        parentRow.classList.add('is-key');
                    }
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateHighlight);
            });

            // Jalankan sekali saat load (untuk handle old input)
            updateHighlight();
        });
    </script>
@endsection
