@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Buat Form Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.form.store') }}" method="POST">
                    @csrf

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label class="form-label">Judul Formulir / Ujian</label>
                        <input type="text" name="judul" class="form-control form-control-lg"
                            placeholder="Contoh: Ujian Kompetensi Perawat 2024" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Petunjuk</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi form..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" class="form-control" required>
                        </div>
                    </div>

                    <hr>

                    {{-- Pilihan Target --}}
                    <div class="mb-3">
                        <label class="form-label d-block fw-bold">Target Peserta</label>

                        <div class="form-check form-check-inline">
                            {{-- Tambahkan ID unik untuk script listener --}}
                            <input class="form-check-input target-radio" type="radio" name="target_peserta" id="semua"
                                value="semua" checked>
                            <label class="form-check-label" for="semua">Semua Perawat</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input target-radio" type="radio" name="target_peserta" id="khusus"
                                value="khusus">
                            <label class="form-check-label" for="khusus">Khusus (Pilih Manual)</label>
                        </div>
                    </div>

                    {{-- Daftar Peserta (Hidden by default) --}}
                    <div id="list-peserta" class="card p-3 bg-light border-0 mb-4" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label text-muted small mb-0">Silakan centang perawat yang diizinkan:</label>

                            {{-- Legenda kecil --}}
                            <small class="text-danger fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-circle-fill"></i> Dokumen akan / sudah expired
                            </small>
                        </div>

                        <div class="row" style="max-height: 300px; overflow-y: auto;">
                            @foreach ($users as $user)
                                @php
                                    $isUrgent =
                                        !empty($user->tgl_expired) && $user->tgl_expired <= now()->addMonth();

                                    $bgClass = $isUrgent ? 'bg-warning-subtle border-warning' : '';
                                    $textClass = $isUrgent ? 'text-dark fw-bold' : 'text-secondary';
                                @endphp

                                <div class="col-md-6 mb-2">
                                    <div class="form-check p-2 rounded {{ $bgClass }}">
                                        <input class="form-check-input" type="checkbox" name="participants[]"
                                            value="{{ $user->id }}" id="user_{{ $user->id }}">

                                        <label class="form-check-label w-100 {{ $textClass }}"
                                            for="user_{{ $user->id }}">
                                            {{ $user->name }}

                                            @if ($isUrgent)
                                                {{-- Badge Peringatan --}}
                                                <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Check
                                                    Dokumen!</span>
                                            @endif

                                            <br>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                {{ $user->email ?? $user->nip }}
                                                {{-- Tampilkan tanggal expired biar jelas --}}
                                                @if (!empty($user->tgl_expired))
                                                    | Exp:
                                                    {{ \Carbon\Carbon::parse($user->tgl_expired)->format('d M Y') }}
                                                @endif
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($users->isEmpty())
                            <div class="text-danger small">Belum ada data perawat di database.</div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.form.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="target_peserta"]');
            const listDiv = document.getElementById('list-peserta');

            function checkTarget() {
                const selected = document.querySelector('input[name="target_peserta"]:checked').value;
                if (selected === 'khusus') {
                    listDiv.style.display = 'block';
                } else {
                    listDiv.style.display = 'none';
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', checkTarget);
            });

            checkTarget();
        });
    </script>
@endsection
