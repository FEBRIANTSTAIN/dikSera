@extends('layouts.app')

@section('title', 'Ujian: ' . $form->judul)

@push('styles')
    <style>
        /* --- Layout & Spacing --- */
        .exam-container {
            padding-bottom: 100px;
        }

        /* --- Question Card --- */
        .question-card {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s;
            scroll-margin-top: 100px;
            /* Agar saat jump link tidak tertutup header */
        }

        .question-badge {
            width: 32px;
            height: 32px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* --- Custom Radio Options --- */
        .option-label {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background: #fff;
            position: relative;
        }

        .option-label:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* State: Checked */
        .btn-check:checked+.option-label {
            background-color: #eff6ff;
            /* Light Blue */
            border-color: #3b82f6;
            /* Blue Main */
            box-shadow: 0 0 0 1px #3b82f6;
            color: #1e40af;
        }

        /* Circle Indicator di dalam opsi */
        .option-circle {
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .option-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            background: #3b82f6;
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s;
        }

        .btn-check:checked+.option-label .option-circle {
            border-color: #3b82f6;
        }

        .btn-check:checked+.option-label .option-circle::after {
            opacity: 1;
            transform: scale(1);
        }

        /* --- Sidebar Navigation (Desktop) --- */
        .sidebar-sticky {
            position: sticky;
            top: 90px;
            /* Sesuaikan dengan tinggi navbar */
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .nav-item-box {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .nav-item-box:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .nav-item-box.answered {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        /* --- Timer Box --- */
        .timer-box {
            background: #fff;
            border: 1px solid #fee2e2;
            color: #dc2626;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .timer-time {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            line-height: 1;
        }

        /* --- Mobile Sticky Footer --- */
        .mobile-footer {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar-sticky {
                display: none;
                /* Hide sidebar on mobile */
            }

            .mobile-footer {
                display: block;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                padding: 15px;
                border-top: 1px solid #e2e8f0;
                z-index: 1030;
                box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
            }

            .exam-container {
                padding-bottom: 120px;
                /* Space for footer */
            }
        }
    </style>
@endpush

@section('content')

    {{-- Progress Bar Top --}}
    <div class="progress" style="height: 4px; border-radius: 0;">
        <div class="progress-bar bg-primary" role="progressbar" id="progressBar" style="width: 0%"></div>
    </div>

    <div class="container-fluid mt-4 exam-container">
        <form action="{{ route('perawat.ujian.submit', $form->slug) }}" method="POST" id="examForm">
            @csrf

            <div class="row g-4">

                {{-- KOLOM KIRI: SOAL --}}
                <div class="col-lg-8">

                    {{-- Header Mobile (Timer Only) --}}
                    <div class="d-lg-none mb-3">
                        <div
                            class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm border">
                            <span class="fw-bold text-truncate" style="max-width: 200px;">{{ $form->judul }}</span>
                            <span class="badge bg-danger bg-opacity-10 text-danger" id="mobile-timer">--:--:--</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Lembar Soal</h5>
                        <span class="text-muted small">Total: {{ $questions->count() }} Soal</span>
                    </div>

                    @forelse($questions as $index => $soal)
                        <div class="card question-card bg-white p-4 mb-4" id="soal-{{ $index + 1 }}">
                            {{-- Header Soal --}}
                            <div class="d-flex gap-3 mb-3">
                                <div class="question-badge">{{ $index + 1 }}</div>
                                <div class="flex-grow-1">
                                    <h6 class="text-dark lh-base mb-0" style="font-size: 16px; font-weight: 500;">
                                        {!! nl2br(e($soal->pertanyaan)) !!}
                                    </h6>
                                </div>
                            </div>

                            {{-- Opsi Jawaban --}}
                            <div class="d-flex flex-column gap-2 ms-md-5">
                                {{-- 
                                    Tips: Sebaiknya hindari shuffle di view jika ingin melacak analisis butir soal per opsi (A/B/C/D).
                                    Jika tetap ingin shuffle, pastikan value input tetap key asli ('a', 'b', etc).
                                --}}
                                @foreach (['a', 'b', 'c', 'd', 'e'] as $optKey)
                                    @if (isset($soal->opsi_jawaban[$optKey]) && !empty($soal->opsi_jawaban[$optKey]))
                                        <div>
                                            <input type="radio" class="btn-check answer-radio"
                                                name="answers[{{ $soal->id }}]"
                                                id="q_{{ $soal->id }}_{{ $optKey }}" value="{{ $optKey }}"
                                                data-index="{{ $index + 1 }}" autocomplete="off">

                                            <label class="option-label" for="q_{{ $soal->id }}_{{ $optKey }}">
                                                <div class="option-circle"></div>
                                                <div class="flex-grow-1 text-dark">{{ $soal->opsi_jawaban[$optKey] }}</div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center py-5">
                            <i class="bi bi-info-circle display-4 mb-3 d-block"></i>
                            Tidak ada soal yang tersedia untuk ujian ini.
                        </div>
                    @endforelse
                </div>

                {{-- KOLOM KANAN: NAVIGASI & TIMER (Sticky Desktop) --}}
                <div class="col-lg-4">
                    <div class="sidebar-sticky">

                        {{-- Timer Card --}}
                        <div class="timer-box">
                            <div class="small text-uppercase fw-bold mb-1 opacity-75">Sisa Waktu</div>
                            <div id="desktop-timer" class="timer-time">--:--:--</div>
                        </div>

                        {{-- Navigasi Soal Card --}}
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-white py-3 border-bottom-0">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i> Navigasi Soal</h6>
                            </div>
                            <div class="card-body pt-0">
                                <div class="nav-grid">
                                    @foreach ($questions as $index => $soal)
                                        <a href="#soal-{{ $index + 1 }}" id="nav-item-{{ $index + 1 }}"
                                            class="nav-item-box">
                                            {{ $index + 1 }}
                                        </a>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-3 mt-3 justify-content-center">
                                    <div class="d-flex align-items-center gap-1 small text-muted">
                                        <span
                                            style="width:10px; height:10px; background:#f1f5f9; display:inline-block; border-radius:2px; border:1px solid #cbd5e1;"></span>
                                        Kosong
                                    </div>
                                    <div class="d-flex align-items-center gap-1 small text-muted">
                                        <span
                                            style="width:10px; height:10px; background:#2563eb; display:inline-block; border-radius:2px;"></span>
                                        Terjawab
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit Desktop --}}
                        <button type="button" class="btn btn-primary w-100 py-3 fw-bold shadow-sm"
                            onclick="confirmSubmit()">
                            <i class="bi bi-send-fill me-2"></i> Kumpulkan Jawaban
                        </button>
                    </div>
                </div>
            </div>

            {{-- FOOTER MOBILE --}}
            <div class="mobile-footer">
                <div class="row align-items-center">
                    <div class="col-6">
                        <small class="text-muted d-block">Terjawab</small>
                        <span class="fw-bold text-primary fs-5"><span id="answered-count">0</span> /
                            {{ $questions->count() }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-primary w-100 fw-bold" onclick="confirmSubmit()">
                            Selesai <i class="bi bi-check-lg ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    {{-- Script JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Logic Timer
            // Ganti format tanggal agar kompatibel dengan Safari (YYYY/MM/DD) jika perlu, tapi format standar ISO biasanya oke.
            var countDownDate = new Date("{{ $form->waktu_selesai->format('Y-m-d H:i:s') }}").getTime();

            var timerInterval = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    document.getElementById("desktop-timer").innerHTML = "00:00:00";
                    if (document.getElementById("mobile-timer")) document.getElementById("mobile-timer")
                        .innerHTML = "00:00:00";

                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu Habis!',
                        text: 'Jawaban Anda akan dikumpulkan secara otomatis.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        document.getElementById("examForm").submit();
                    });
                    return;
                }

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Formatting 2 digit (01:05:09)
                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                var textTime = hours + ":" + minutes + ":" + seconds;

                document.getElementById("desktop-timer").innerHTML = textTime;
                if (document.getElementById("mobile-timer")) document.getElementById("mobile-timer")
                    .innerHTML = textTime;

                // Visual Warning jika < 5 menit
                if (distance < 5 * 60 * 1000) {
                    document.querySelector('.timer-box').style.borderColor = '#dc2626';
                    document.querySelector('.timer-box').classList.add('animate__animated',
                        'animate__pulse', 'animate__infinite');
                }

            }, 1000);

            // 2. Logic Navigasi Soal & Progress
            const radios = document.querySelectorAll('.answer-radio');
            const totalQuestions = {{ $questions->count() }};

            function updateProgress() {
                const answered = document.querySelectorAll('.answer-radio:checked').length;

                // Update Sidebar Box Color
                document.querySelectorAll('.answer-radio:checked').forEach(radio => {
                    const idx = radio.getAttribute('data-index');
                    const navBox = document.getElementById('nav-item-' + idx);
                    if (navBox) navBox.classList.add('answered');
                });

                // Update Mobile Counter
                if (document.getElementById('answered-count')) {
                    document.getElementById('answered-count').innerText = answered;
                }

                // Update Top Progress Bar
                const percent = (answered / totalQuestions) * 100;
                document.getElementById('progressBar').style.width = percent + "%";
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateProgress);
            });

            // Init (jika user refresh, tandai yg checked dari old input jika ada browser cache)
            updateProgress();
        });

        // 3. Confirm Submit
        function confirmSubmit() {
            // Hitung soal yg belum dijawab
            const total = {{ $questions->count() }};
            const answered = document.querySelectorAll('.answer-radio:checked').length;
            const unanswered = total - answered;

            let warningText = "Pastikan Anda sudah memeriksa kembali semua jawaban.";
            if (unanswered > 0) {
                warningText = "Masih ada " + unanswered + " soal yang BELUM dijawab! Yakin ingin mengumpulkan?";
            }

            Swal.fire({
                title: 'Kumpulkan Jawaban?',
                text: warningText,
                icon: unanswered > 0 ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Periksa Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("examForm").submit();
                }
            });
        }
    </script>
@endsection
