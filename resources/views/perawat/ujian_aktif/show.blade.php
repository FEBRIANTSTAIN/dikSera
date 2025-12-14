@extends('layouts.app')

@section('title', 'Detail Ujian: ' . $form->judul)

@push('styles')
    <style>
        /* --- 0. BUG FIX: PASTIKAN STICKY BERJALAN --- */
        /* Memaksa elemen induk agar tidak menyembunyikan overflow */
        body,
        html,
        .wrapper,
        .main-content {
            overflow: visible !important;
        }

        /* --- 1. Header Styling --- */
        .exam-header {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border-bottom: 1px solid #f1f5f9;
            padding: 40px 30px;
            border-radius: 16px 16px 0 0;
            position: relative;
            overflow: hidden;
        }

        /* Hiasan background abstrak */
        .exam-header::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, #dbeafe 0%, transparent 70%);
            opacity: 0.6;
        }

        /* --- 2. Content & Typography --- */
        .description-content {
            line-height: 1.8;
            color: #334155;
            font-size: 1rem;
        }

        /* Handling Iframe (Google Form/Youtube) Responsiveness */
        .description-content iframe,
        .description-content embed {
            width: 100% !important;
            max-width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin: 15px 0;
            display: block;
        }

        .description-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* --- 3. Badges --- */
        .badge-pill {
            padding: 8px 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
        }

        .badge-blue {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        .badge-purple {
            background: #faf5ff;
            color: #7e22ce;
            border: 1px solid #f3e8ff;
        }

        .badge-green {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
        }

        .badge-gray {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* --- 4. Sidebar Info Box (Desktop Sticky) --- */
        .info-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);

            /* >>> KUNCI STICKY <<< */
            position: -webkit-sticky;
            /* Safari */
            position: sticky;
            top: 100px;
            /* Jarak dari atas */
            z-index: 1020;
            align-self: flex-start;
            /* Mencegah card ketarik full height */
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-right: 15px;
            flex-shrink: 0;
        }

        /* --- 5. Mobile Fixed Footer Action --- */
        .mobile-action-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 16px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
            border-top: 1px solid #e2e8f0;
            z-index: 1050;
            text-align: center;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .mobile-action-bar {
                display: block;
            }

            .desktop-action-btn {
                display: none;
            }

            .exam-header {
                padding: 24px 20px;
            }

            .main-content-wrapper {
                padding-bottom: 80px;
            }

            /* Reset sticky di mobile biar rapi */
            .info-card {
                position: relative;
                top: 0;
                z-index: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3 main-content-wrapper">

        {{-- Breadcrumb / Back --}}
        <div class="mb-3">
            <a href="{{ route('perawat.ujian.index') }}" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> KEMBALI KE DAFTAR
            </a>
        </div>

        <div class="row g-4">

            {{-- KOLOM KIRI: KONTEN UTAMA --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">

                    {{-- Header --}}
                    <div class="exam-header">
                        {{-- Badges --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if ($form->target_peserta == 'khusus')
                                <span class="badge-pill badge-purple"><i class="bi bi-lock-fill"></i> Undangan Khusus</span>
                            @else
                                <span class="badge-pill badge-blue"><i class="bi bi-globe"></i> Public Access</span>
                            @endif

                            @if ($form->status == 'publish')
                                <span class="badge-pill badge-green"><i class="bi bi-check-circle-fill"></i>
                                    Published</span>
                            @else
                                <span class="badge-pill badge-gray"><i class="bi bi-dash-circle-fill"></i>
                                    {{ ucfirst($form->status) }}</span>
                            @endif
                        </div>

                        <h2 class="fw-bold text-dark mb-2">{{ $form->judul }}</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-clock me-1"></i> Diposting pada {{ $form->created_at->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Body: Deskripsi --}}
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                            <span class="bg-primary bg-opacity-10 text-primary rounded px-2 py-1">
                                <i class="bi bi-file-text-fill"></i>
                            </span>
                            <h5 class="fw-bold m-0">Deskripsi & Instruksi</h5>
                        </div>

                        <div class="description-content">
                            @if ($form->deskripsi)
                                {!! $form->deskripsi !!}
                            @else
                                <div class="alert alert-light border-dashed text-center text-muted">
                                    Tidak ada instruksi khusus untuk ujian ini. Silakan langsung mengerjakan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: SIDEBAR INFO & AKSI (Sticky) --}}
            <div class="col-lg-4">
                {{-- INFO CARD INI AKAN STICKY --}}
                <div class="info-card">
                    <h6 class="fw-bold mb-4 text-dark">Detail Pelaksanaan</h6>

                    {{-- Mulai --}}
                    <div class="info-row">
                        <div class="info-icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted" style="font-size: 10px; font-weight: 700;">Waktu Mulai
                            </div>
                            <div class="fw-bold text-dark">{{ $form->waktu_mulai->format('d M Y') }}</div>
                            <div class="small text-muted">{{ $form->waktu_mulai->format('H:i') }} WIB</div>
                        </div>
                    </div>

                    {{-- Selesai --}}
                    <div class="info-row">
                        <div class="info-icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted" style="font-size: 10px; font-weight: 700;">Batas Akhir
                            </div>
                            <div class="fw-bold text-dark">{{ $form->waktu_selesai->format('d M Y') }}</div>
                            <div class="small text-muted">{{ $form->waktu_selesai->format('H:i') }} WIB</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        {{-- Logic Tombol (Desktop) --}}
                        @php
                            $now = \Carbon\Carbon::now();
                            $isStarted = $now->greaterThanOrEqualTo($form->waktu_mulai);
                            $isEnded = $now->greaterThan($form->waktu_selesai);
                        @endphp

                        <div class="desktop-action-btn">
                            @if ($isStarted && !$isEnded)
                                <a href="{{ route('perawat.ujian.kerjakan', $form->slug) }}"
                                    class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-3">
                                    <i class="bi bi-play-circle-fill me-2"></i> Mulai Kerjakan Sekarang
                                </a>
                                <div class="text-center mt-2">
                                    <small class="text-muted" style="font-size: 11px;">Pastikan koneksi internet
                                        stabil</small>
                                </div>
                            @elseif(!$isStarted)
                                <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                    <i class="bi bi-lock-fill me-2"></i> Belum Dimulai
                                </button>
                                <div class="text-center mt-2">
                                    <small class="text-danger fw-bold" style="font-size: 11px;">
                                        Buka pada: {{ $form->waktu_mulai->diffForHumans() }}
                                    </small>
                                </div>
                            @else
                                <button class="btn btn-light border w-100 py-3 fw-bold text-muted disabled" disabled>
                                    <i class="bi bi-x-circle-fill me-2"></i> Waktu Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE FIXED ACTION BAR (Sticky Footer) --}}
    <div class="mobile-action-bar">
        <div class="container-fluid px-0">
            @if ($isStarted && !$isEnded)
                <div class="d-flex align-items-center gap-3">
                    <div class="text-start flex-grow-1 lh-1">
                        <div class="text-uppercase text-muted" style="font-size: 10px;">Sisa Waktu</div>
                        <div class="fw-bold text-dark small">Tersedia</div>
                    </div>
                    <a href="{{ route('perawat.ujian.kerjakan', $form->slug) }}"
                        class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm flex-grow-1">
                        Mulai Kerjakan
                    </a>
                </div>
            @elseif(!$isStarted)
                <button class="btn btn-secondary w-100 py-2 rounded-pill fw-bold disabled">
                    Belum Dimulai
                </button>
            @else
                <button class="btn btn-light border w-100 py-2 rounded-pill fw-bold text-muted disabled">
                    Waktu Habis
                </button>
            @endif
        </div>
    </div>

@endsection
