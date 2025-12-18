@extends('layouts.app')

@section('title', 'Detail Pengajuan - ' . $pengajuan->user->name)

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-body: #f8fafc;
            --card-border: #e2e8f0;

            /* Status Colors */
            --st-pending-bg: #f1f5f9;
            --st-pending-text: #475569;
            --st-info-bg: #eff6ff;
            --st-info-text: #1d4ed8;
            --st-success-bg: #f0fdf4;
            --st-success-text: #166534;
            --st-danger-bg: #fef2f2;
            --st-danger-text: #b91c1c;
            --st-warning-bg: #fffbeb;
            --st-warning-text: #b45309;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-enter {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .page-id {
            font-family: monospace;
            color: var(--text-gray);
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .detail-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--card-border);
            padding: 0;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .card-header-clean {
            padding: 20px 24px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body-clean {
            padding: 24px;
        }

        .info-group {
            margin-bottom: 16px;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-gray);
            font-weight: 600;
            letter-spacing: 0.025em;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .score-widget {
            background: #f8fafc;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            height: 100%;
            transition: transform 0.2s;
        }

        .score-widget:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }

        .score-number {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .score-text {
            font-size: 0.8rem;
            color: var(--text-gray);
            font-weight: 500;
        }

        .text-score-primary {
            color: var(--primary-blue);
        }

        .text-score-success {
            color: #16a34a;
        }

        .text-score-danger {
            color: #dc2626;
        }

        .timeline {
            position: relative;
            padding-left: 10px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-left: 24px;
            padding-bottom: 24px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 4px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: white;
            border: 3px solid #cbd5e1;
            z-index: 1;
            transition: all 0.3s;
        }

        .timeline-item.active .timeline-dot {
            border-color: var(--primary-blue);
            background: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
        }

        .timeline-content h6 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 2px 0;
        }

        .timeline-content p {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin: 0;
        }

        .status-pill {
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .st-pending {
            background: var(--st-pending-bg);
            color: var(--st-pending-text);
        }

        .st-info {
            background: var(--st-info-bg);
            color: var(--st-info-text);
        }

        .st-success {
            background: var(--st-success-bg);
            color: var(--st-success-text);
        }

        .st-danger {
            background: var(--st-danger-bg);
            color: var(--st-danger-text);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        {{-- HEADER --}}
        <div class="page-header animate-enter">
            <div>
                <div class="d-flex align-items-center gap-3 mb-1">
                    <h1 class="page-title">Detail Pengajuan</h1>
                    <span class="page-id">#{{ $pengajuan->id }}</span>
                </div>
                <p class="text-muted small mb-0">Tinjau detail permohonan, hasil tes, dan riwayat proses.</p>
            </div>
            <a href="{{ route('admin.pengajuan.index') }}"
                class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row">

            {{-- KOLOM KIRI: INFO UTAMA --}}
            <div class="col-lg-8">

                {{-- 1. Info Peserta --}}
                <div class="detail-card animate-enter delay-1">
                    <div class="card-header-clean">
                        <h5 class="card-title"><i class="bi bi-person-lines-fill text-secondary"></i> Informasi Pemohon
                        </h5>
                    </div>
                    <div class="card-body-clean">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Nama Lengkap</div>
                                    <div class="info-value">{{ $pengajuan->user->name }}</div>
                                </div>
                                <div class="info-group mb-0">
                                    <div class="info-label">Email Kontak</div>
                                    <div class="info-value text-break">{{ $pengajuan->user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 border-start border-light ps-md-4">
                                <div class="info-group">
                                    <div class="info-label">Lisensi yang Diperpanjang</div>
                                    <div class="info-value">{{ $pengajuan->lisensiLama->nama ?? '-' }}</div>
                                </div>
                                <div class="info-group mb-0">
                                    <div class="info-label">Metode Evaluasi Terpilih</div>
                                    <div class="info-value text-primary">
                                        @if ($pengajuan->metode == 'pg_only')
                                            <i class="bi bi-file-text me-1"></i> Hanya Ujian Tulis
                                        @else
                                            <i class="bi bi-people me-1"></i> Ujian Tulis + Wawancara
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Hasil Ujian Tulis (Stats Widgets) --}}
                @if ($pengajuan->user && $pengajuan->user->examResult)
                    @php
                        $result = $pengajuan->user->examResult;

                        // LOGIKA STATUS LULUS:
                        // Lulus jika di DB (kolom lulus) = 1, ATAU jika status pengajuan sudah tahap lanjut (Acc Admin)
                        $isPassed =
                            $result->lulus == 1 ||
                            in_array($pengajuan->status, ['exam_passed', 'interview_scheduled', 'completed']);

                        // [PERBAIKAN] Menggunakan nama kolom sesuai Migration: total_benar & total_salah
                        $tampilBenar = $result->total_benar ?? 0;
                        $tampilSalah = $result->total_salah ?? 0;
                    @endphp

                    <div class="detail-card animate-enter delay-1">
                        <div class="card-header-clean">
                            <h5 class="card-title"><i class="bi bi-laptop text-secondary"></i> Hasil Computer Based Test
                                (CBT)</h5>

                            <span class="badge {{ $isPassed ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $isPassed ? 'LULUS PASSING GRADE' : 'TIDAK LULUS' }}
                            </span>
                        </div>
                        <div class="card-body-clean">
                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <div class="score-widget">
                                        <div class="score-number text-score-primary">{{ $result->total_nilai ?? 0 }}</div>
                                        <div class="score-text">Total Skor</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="score-widget">
                                        {{-- Tampilkan angka murni --}}
                                        <div class="score-number text-score-success">{{ $tampilBenar }}</div>
                                        <div class="score-text">Jawaban Benar</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="score-widget">
                                        {{-- Tampilkan angka murni --}}
                                        <div class="score-number text-score-danger">{{ $tampilSalah }}</div>
                                        <div class="score-text">Jawaban Salah</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted fst-italic">
                                    <i class="bi bi-clock-history me-1"></i> Diselesaikan pada
                                    {{ \Carbon\Carbon::parse($result->created_at)->format('d F Y, H:i') }} WIB
                                </small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="detail-card animate-enter delay-1">
                        <div class="card-body-clean">
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-exclamation-circle fs-3 d-block mb-2"></i>
                                <span class="fw-medium">Hasil Ujian Belum Tersedia</span>
                                <p class="small mb-0">Peserta belum menyelesaikan ujian tulis.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 3. Detail Wawancara --}}
                @if ($pengajuan->jadwalWawancara)
                    @php $jadwal = $pengajuan->jadwalWawancara; @endphp
                    <div class="detail-card animate-enter delay-1">
                        <div class="card-header-clean">
                            <h5 class="card-title"><i class="bi bi-mic text-secondary"></i> Sesi Wawancara</h5>
                            @if ($jadwal->penilaian)
                                <span
                                    class="badge {{ $jadwal->penilaian->keputusan == 'lulus' ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                    HASIL: {{ strtoupper(str_replace('_', ' ', $jadwal->penilaian->keputusan)) }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">Belum Dinilai</span>
                            @endif
                        </div>
                        <div class="card-body-clean">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <div class="info-label">Pewawancara</div>
                                        <div class="info-value">{{ $jadwal->pewawancara->nama ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <div class="info-label">Jadwal & Lokasi</div>
                                        <div class="info-value">
                                            {{ \Carbon\Carbon::parse($jadwal->waktu_wawancara)->format('d M Y, H:i') }}
                                            <br>
                                            <span class="fw-normal text-muted small">@ {{ $jadwal->lokasi }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($jadwal->penilaian)
                                <div class="p-3 bg-light border rounded-3">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Rincian Skor Penilaian</h6>
                                    <div class="row g-2 text-center">
                                        <div class="col-4 border-end">
                                            <div class="fw-bold fs-5 text-dark">{{ $jadwal->penilaian->skor_kompetensi }}
                                            </div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Kompetensi</small>
                                        </div>
                                        <div class="col-4 border-end">
                                            <div class="fw-bold fs-5 text-dark">{{ $jadwal->penilaian->skor_sikap }}</div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Sikap</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold fs-5 text-dark">{{ $jadwal->penilaian->skor_pengetahuan }}
                                            </div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Pengetahuan</small>
                                        </div>
                                    </div>
                                    @if ($jadwal->penilaian->catatan)
                                        <hr class="my-3 opacity-25">
                                        <div class="d-flex gap-2">
                                            <i class="bi bi-chat-quote-fill text-secondary opacity-50"></i>
                                            <p class="mb-0 small text-secondary fst-italic">
                                                "{{ $jadwal->penilaian->catatan }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            {{-- KOLOM KANAN: ACTION & TIMELINE --}}
            <div class="col-lg-4">

                {{-- Status & Actions --}}
                <div class="detail-card animate-enter delay-2">
                    <div class="card-body-clean text-center">
                        <h6 class="info-label mb-3">Status Pengajuan Saat Ini</h6>

                        <div class="mb-4">
                            @if ($pengajuan->status == 'pending')
                                <span class="status-pill st-pending"><i class="bi bi-hourglass-split"></i> Menunggu
                                    Approval Admin</span>
                            @elseif($pengajuan->status == 'method_selected')
                                <span class="status-pill st-info"><i class="bi bi-pencil-square"></i> Sedang Ujian
                                    Tulis</span>
                            @elseif($pengajuan->status == 'exam_passed')
                                <span class="status-pill st-info"><i class="bi bi-pause-circle"></i> Menunggu
                                    Wawancara</span>
                            @elseif($pengajuan->status == 'interview_scheduled')
                                <span class="status-pill st-info"><i class="bi bi-calendar-check"></i> Wawancara
                                    Terjadwal</span>
                            @elseif($pengajuan->status == 'completed')
                                <span class="status-pill st-success"><i class="bi bi-check-circle-fill"></i> Selesai
                                    (Disetujui)</span>
                            @elseif($pengajuan->status == 'rejected')
                                <span class="status-pill st-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        @if ($pengajuan->status == 'pending')
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.pengajuan.approve', $pengajuan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                        <i class="bi bi-check-lg me-1"></i> Setujui Pengajuan
                                    </button>
                                </form>
                                <a href="{{ route('admin.pengajuan.reject', $pengajuan->id) }}"
                                    class="btn btn-outline-danger w-100 fw-bold py-2"
                                    onclick="return confirm('Tolak pengajuan ini?')">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </a>
                            </div>
                        @else
                            <div class="p-3 bg-light rounded text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Tidak ada tindakan yang diperlukan saat ini.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="detail-card animate-enter delay-2">
                    <div class="card-header-clean py-3">
                        <h6 class="card-title" style="font-size: 0.9rem;">Riwayat Proses</h6>
                    </div>
                    <div class="card-body-clean">
                        <div class="timeline">
                            <div class="timeline-item active">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h6>Pengajuan Dibuat</h6>
                                    <p>{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            @if (in_array($pengajuan->status, ['method_selected', 'exam_passed', 'interview_scheduled', 'completed']))
                                <div class="timeline-item active">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h6>Disetujui Admin</h6>
                                        <p>Metode: {{ $pengajuan->metode == 'pg_only' ? 'Tulis' : 'Tulis + Wawancara' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($pengajuan->user->examResult)
                                @php
                                    $result = $pengajuan->user->examResult;
                                    $isPassed =
                                        $result->lulus == 1 ||
                                        in_array($pengajuan->status, [
                                            'exam_passed',
                                            'interview_scheduled',
                                            'completed',
                                        ]);
                                @endphp
                                <div class="timeline-item active">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h6>Ujian Tulis Selesai</h6>
                                        <p class="{{ $isPassed ? 'text-success' : 'text-danger' }}">
                                            Skor: {{ $result->total_nilai }} ({{ $isPassed ? 'Lulus' : 'Gagal' }})
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($pengajuan->jadwalWawancara)
                                <div class="timeline-item active">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h6>Jadwal Wawancara Diajukan</h6>
                                        <p>{{ \Carbon\Carbon::parse($pengajuan->jadwalWawancara->created_at)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                @if ($pengajuan->jadwalWawancara->status == 'approved' || $pengajuan->jadwalWawancara->status == 'completed')
                                    <div class="timeline-item active">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <h6>Jadwal Disetujui</h6>
                                            <p class="text-muted">Siap dilaksanakan</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($pengajuan->jadwalWawancara->penilaian)
                                    <div class="timeline-item active">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <h6>Wawancara Selesai</h6>
                                            <p
                                                class="{{ $pengajuan->jadwalWawancara->penilaian->keputusan == 'lulus' ? 'text-success' : 'text-danger' }}">
                                                Hasil:
                                                {{ ucfirst($pengajuan->jadwalWawancara->penilaian->keputusan) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if ($pengajuan->status == 'completed')
                                <div class="timeline-item active">
                                    <div class="timeline-dot"
                                        style="background: var(--st-success-text); border-color: var(--st-success-text);">
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="text-success fw-bold">Proses Selesai</h6>
                                        <p class="text-success">Lisensi Berhasil Diperpanjang</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
