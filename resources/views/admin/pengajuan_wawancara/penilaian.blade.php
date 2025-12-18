@extends('layouts.app')

@section('title', 'Penilaian Wawancara')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-blue: #2563eb;
        --primary-hover: #1d4ed8;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --bg-body: #f8fafc;
        --card-border: #e2e8f0;

        /* Decision Colors */
        --success-soft: #f0fdf4;
        --success-border: #86efac;
        --success-text: #166534;

        --danger-soft: #fef2f2;
        --danger-border: #fca5a5;
        --danger-text: #991b1b;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
    }

    /* --- ANIMATIONS --- */
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .animate-enter {
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }

    /* --- Layout Components --- */
    .page-header {
        margin-bottom: 2rem;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        margin: 0;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        padding: 0; /* Padding handled by children */
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }

    /* --- Info Header (Ticket Style) --- */
    .info-header {
        background-color: #f8fafc;
        border-bottom: 1px dashed #cbd5e1;
        padding: 24px 32px;
    }
    .info-label { font-size: 0.75rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 4px; }
    .info-value { font-size: 1rem; font-weight: 700; color: var(--text-dark); }

    .form-body {
        padding: 32px;
    }

    /* --- Inputs --- */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label { font-weight: 600; font-size: 0.9rem; color: var(--text-dark); margin-bottom: 8px; }

    .score-input-group {
        position: relative;
    }
    .score-input {
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .score-input:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .score-suffix {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-gray);
        font-size: 0.85rem;
        font-weight: 500;
        pointer-events: none;
    }

    /* --- Custom Decision Radio Cards --- */
    .decision-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .decision-card {
        position: relative;
    }

    /* Hide default radio */
    .decision-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .decision-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        border: 2px solid var(--card-border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        background: white;
        color: var(--text-gray);
    }

    .decision-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        transition: transform 0.3s;
    }

    .decision-text {
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Hover Effects */
    .decision-label:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
    }

    /* Checked State: LULUS */
    .decision-card input[value="lulus"]:checked + .decision-label {
        border-color: var(--success-border);
        background-color: var(--success-soft);
        color: var(--success-text);
        box-shadow: 0 4px 12px rgba(22, 101, 52, 0.15);
    }

    /* Checked State: TIDAK LULUS */
    .decision-card input[value="tidak_lulus"]:checked + .decision-label {
        border-color: var(--danger-border);
        background-color: var(--danger-soft);
        color: var(--danger-text);
        box-shadow: 0 4px 12px rgba(153, 27, 27, 0.15);
    }

    /* Icon Animation on Check */
    .decision-card input:checked + .decision-label .decision-icon {
        transform: scale(1.2);
    }

    .btn-submit {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-submit:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-end animate-enter">
        <div>
            <h1 class="page-title">Input Penilaian Wawancara</h1>
            <p class="text-muted mt-1 mb-0">Evaluasi kompetensi peserta dan tentukan hasil akhir.</p>
        </div>
        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="form-card animate-enter animate-delay-1">
        <form action="{{ route('admin.pengajuan_wawancara.store_penilaian', $jadwal->id) }}" method="POST">
            @csrf

            {{-- Info Peserta (Ticket Style) --}}
            <div class="info-header">
                <div class="row align-items-center g-4">
                    <div class="col-md-6 border-end border-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-3 rounded-circle shadow-sm border text-primary">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <div class="info-label">Nama Peserta</div>
                                <div class="info-value">{{ $jadwal->pengajuan->user->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 ps-md-3">
                            <div class="bg-white p-3 rounded-circle shadow-sm border text-info">
                                <i class="bi bi-person-video2 fs-4"></i>
                            </div>
                            <div>
                                <div class="info-label">Pewawancara</div>
                                <div class="info-value">{{ $jadwal->pewawancara->nama }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body">

                {{-- A. POIN PENILAIAN --}}
                <div class="mb-5">
                    <div class="section-title text-primary">
                        <i class="bi bi-calculator"></i> A. Poin Penilaian (0-100)
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Kompetensi Teknis</label>
                            <div class="score-input-group">
                                <input type="number" name="skor_kompetensi" class="form-control score-input" min="0" max="100" placeholder="0" required>
                                <span class="score-suffix">/100</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sikap & Etika</label>
                            <div class="score-input-group">
                                <input type="number" name="skor_sikap" class="form-control score-input" min="0" max="100" placeholder="0" required>
                                <span class="score-suffix">/100</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pengetahuan Umum</label>
                            <div class="score-input-group">
                                <input type="number" name="skor_pengetahuan" class="form-control score-input" min="0" max="100" placeholder="0" required>
                                <span class="score-suffix">/100</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Catatan Detail (Opsional)</label>
                            <textarea name="catatan" class="form-control score-input" rows="3" style="font-weight: 400;" placeholder="Tambahkan catatan khusus mengenai kelebihan/kekurangan peserta..."></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary opacity-10 my-4">

                {{-- B. KEPUTUSAN AKHIR (ANIMATED CARDS) --}}
                <div class="mb-4">
                    <div class="section-title text-dark">
                        <i class="bi bi-gavel"></i> B. Keputusan Akhir
                    </div>
                    <p class="text-muted small mb-3">Pilih rekomendasi status akhir untuk peserta ini. Keputusan tidak dapat diubah setelah disimpan.</p>

                    <div class="decision-wrapper">
                        {{-- Card LULUS --}}
                        <div class="decision-card">
                            <input type="radio" name="keputusan" value="lulus" id="opt_lulus" required>
                            <label for="opt_lulus" class="decision-label">
                                <i class="bi bi-check-circle-fill decision-icon"></i>
                                <span class="decision-text">REKOMENDASI LULUS</span>
                                <small class="fw-normal mt-1 opacity-75">Peserta memenuhi standar</small>
                            </label>
                        </div>

                        {{-- Card TIDAK LULUS --}}
                        <div class="decision-card">
                            <input type="radio" name="keputusan" value="tidak_lulus" id="opt_tidak" required>
                            <label for="opt_tidak" class="decision-label">
                                <i class="bi bi-x-circle-fill decision-icon"></i>
                                <span class="decision-text">TIDAK LULUS</span>
                                <small class="fw-normal mt-1 opacity-75">Peserta belum memenuhi standar</small>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="d-flex justify-content-end pt-3">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save2 me-2"></i> Simpan Hasil Penilaian
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
