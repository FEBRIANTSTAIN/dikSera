@extends('layouts.app')

@php
    $pageTitle = 'Integrasi Telegram';
    $pageSubtitle = 'Hubungkan akun administrator dengan Telegram untuk notifikasi sistem.';
@endphp

@section('title', 'Integrasi Telegram – Admin DIKSERA')

@push('styles')
    <style>
        /* Global Card */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 32px 24px;
            height: 100%;
        }

        /* Telegram Brand Color */
        :root {
            --telegram-color: #24A1DE;
            --telegram-bg: #f2faff;
        }

        /* Icon Box styles */
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
        }

        .icon-box.telegram {
            background-color: var(--telegram-bg);
            color: var(--telegram-color);
        }

        .icon-box.success {
            background-color: #dcfce7;
            color: #166534;
        }

        /* OTP Code Display */
        .otp-box {
            background: #f8fafc;
            border: 2px dashed var(--border-soft);
            border-radius: 12px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }

        .otp-code {
            font-family: 'Courier New', monospace;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 4px;
            color: var(--text-main);
            display: block;
        }

        .otp-timer {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Steps Container */
        .step-container {
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="content-card text-center">

                    {{-- KONDISI 1: SUDAH TERHUBUNG --}}
                    @if (auth()->user()->telegram_chat_id)
                        <div class="py-4">
                            <div class="icon-box success mb-4">
                                <i class="bi bi-check-lg"></i>
                            </div>

                            <h4 class="fw-bold mb-2">Terhubung!</h4>
                            <p class="text-muted mb-4 small px-4">
                                Akun Anda sudah terhubung dengan Telegram.<br>
                                ID Chat: <span
                                    class="font-monospace text-dark bg-light px-2 rounded">{{ auth()->user()->telegram_chat_id }}</span>
                            </p>

                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Tes --}}
                                <form action="{{ route('admin.telegram.test') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" style="border-radius: 8px;">
                                        <i class="bi bi-send me-1"></i> Tes Notifikasi
                                    </button>
                                </form>

                                {{-- Tombol Putus Koneksi --}}
                                <form action="{{ route('admin.telegram.unlink') }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin memutuskan koneksi Telegram?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" style="border-radius: 8px;">
                                        <i class="bi bi-link-45deg me-1"></i> Putuskan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- KONDISI 2: BELUM TERHUBUNG --}}
                    @else
                        {{-- STEP 1: TOMBOL GENERATE --}}
                        <div id="step-start" class="step-container py-3">
                            <div class="icon-box telegram mb-3">
                                <i class="bi bi-telegram"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Hubungkan Telegram</h5>
                            <p class="text-muted small mb-4">
                                Dapatkan notifikasi sistem secara realtime langsung ke aplikasi Telegram Anda.
                            </p>
                            <button class="btn btn-primary px-4 py-2 w-100 shadow-sm"
                                style="background-color: var(--telegram-color); border:none; border-radius: 8px;"
                                onclick="generateCode()">
                                <i class="bi bi-link me-2"></i> Buat Kode Koneksi
                            </button>
                        </div>

                        {{-- STEP 2: TAMPILKAN KODE (Hidden by default) --}}
                        <div id="step-verify" class="step-container py-3" style="display: none;">
                            <div class="mb-3">
                                <span
                                    class="badge bg-warning text-dark bg-opacity-25 text-opacity-75 px-3 py-2 rounded-pill small">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi
                                </span>
                            </div>

                            <p class="small text-muted mb-0">Kirim kode berikut ke Bot Telegram kami:</p>

                            {{-- Area Kode OTP --}}
                            <div class="otp-box">
                                <span id="display-code" class="otp-code">...</span>
                                <div class="mt-2 border-top pt-2">
                                    <span class="otp-timer">Berlaku sampai: <span id="display-time"
                                            class="fw-bold text-dark">-</span></span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="#" id="link-bot" target="_blank" class="btn btn-primary"
                                    style="background-color: var(--telegram-color); border:none; border-radius: 8px;">
                                    <i class="bi bi-telegram me-2"></i> Buka Bot & Kirim Kode
                                </a>
                                <button onclick="location.reload()" class="btn btn-light text-muted border"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Saya sudah kirim, Cek Status
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript Tetap Sama --}}
    <script>
        function generateCode() {
            // Ubah tombol jadi loading
            const btn = document.querySelector('#step-start button');
            const originalText = btn.innerHTML;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            btn.disabled = true;

            fetch("{{ route('admin.telegram.generate') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animasi transisi smooth (opsional, ganti display basic juga oke)
                        document.getElementById('step-start').style.display = 'none';
                        document.getElementById('step-verify').style.display = 'block';

                        // Isi data
                        document.getElementById('display-code').innerText = data.code;

                        // Format waktu agar lebih cantik (Opsional, tergantung format data.expires_at)
                        document.getElementById('display-time').innerText = data.expires_at;

                        document.getElementById('link-bot').href = "https://t.me/" + data.bot_username;
                    } else {
                        alert('Gagal membuat kode. Silakan coba lagi.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }
    </script>
@endsection
