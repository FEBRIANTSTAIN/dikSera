@extends('layouts.app')

@php
    $pageTitle = 'Hasil Ujian';
    $pageSubtitle = 'Rekapitulasi nilai dan jawaban peserta.';
@endphp

@section('title', 'Hasil Ujian – Admin DIKSERA')

@push('styles')
    {{-- CSS SweetAlert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Card Container */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 24px;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border-soft);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        /* Custom Table */
        .table-custom th {
            background-color: var(--blue-soft-2);
            color: var(--text-main);
            font-weight: 600;
            font-size: 12px;
            border-bottom: 2px solid #dbeafe;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            vertical-align: middle;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 12px 16px;
            border-bottom: 1px solid var(--blue-soft-2);
            font-size: 13px;
            color: var(--text-main);
        }

        /* Avatar Styling */
        .avatar-circle {
            width: 36px;
            height: 36px;
            background-color: var(--blue-soft);
            color: var(--blue-main);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .avatar-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Badges */
        .badge-soft {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-soft-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Action Buttons */
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1">Hasil Ujian</h4>
                <div class="text-muted small">
                    Form: <span class="fw-bold text-dark">{{ $form->judul }}</span>
                </div>
            </div>
            <a href="{{ route('admin.form.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                style="border-radius: 8px;">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        {{-- Statistik Ringkas --}}
        <div class="row g-3 mb-4">
            {{-- Total Peserta --}}
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Peserta</div>
                        <div class="fs-4 fw-bold text-dark">{{ $results->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- Rata-rata --}}
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Rata-rata Nilai</div>
                        <div class="fs-4 fw-bold text-dark">
                            {{ $results->count() > 0 ? round($results->avg('total_nilai'), 1) : 0 }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tertinggi --}}
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Nilai Tertinggi</div>
                        <div class="fs-4 fw-bold text-success">
                            {{ $results->max('total_nilai') ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terendah --}}
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-graph-down-arrow"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Nilai Terendah</div>
                        <div class="fs-4 fw-bold text-danger">
                            {{ $results->min('total_nilai') ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Hasil --}}
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Identitas Peserta</th>
                            <th>Waktu Selesai</th>
                            <th class="text-center">Benar / Salah</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $res)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Avatar --}}
                                        <div class="avatar-wrapper">
                                            @if (!empty($res->user->profile->foto_3x4))
                                                <img src="{{ asset('storage/' . $res->user->profile->foto_3x4) }}"
                                                    class="avatar-img" alt="{{ $res->user->name }}">
                                            @else
                                                <div class="avatar-circle">{{ strtoupper(substr($res->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $res->user->name }}</div>
                                            <div class="text-muted small">{{ $res->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($res->waktu_selesai)
                                        <div class="text-dark" style="font-size: 13px;">
                                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($res->waktu_selesai)->format('d M Y') }}
                                        </div>
                                        <div class="text-muted small ms-4">
                                            {{ \Carbon\Carbon::parse($res->waktu_selesai)->format('H:i') }} WIB
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                                            title="Jawaban Benar">
                                            <i class="bi bi-check-lg"></i> {{ $res->total_benar }}
                                        </span>
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"
                                            title="Jawaban Salah">
                                            <i class="bi bi-x-lg"></i> {{ $res->total_salah }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark fs-6">{{ $res->total_nilai }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($res->total_nilai >= 70)
                                        {{-- KKM Sementara 70 --}}
                                        <span class="badge-soft badge-soft-success">Lulus</span>
                                    @else
                                        <span class="badge-soft badge-soft-danger">Remedial</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.form.reset-hasil', $res->id) }}" method="POST"
                                        class="reset-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-outline-danger"
                                            title="Reset Hasil (Kerjakan Ulang)" data-bs-toggle="tooltip">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <i class="bi bi-journal-x display-6 opacity-25"></i>
                                    </div>
                                    <span class="text-muted small">Belum ada peserta yang mengerjakan ujian ini.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Init Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Handle SweetAlert for Reset
            const resetForms = document.querySelectorAll('.reset-form');
            resetForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Reset Hasil Peserta?',
                        text: "Data jawaban dan nilai peserta ini akan dihapus. Peserta harus mengerjakan ulang dari awal.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Reset!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Flash Message
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        });
    </script>
@endpush
