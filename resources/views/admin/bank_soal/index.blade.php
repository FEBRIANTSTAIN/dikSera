@extends('layouts.app')

@php
    $pageTitle = 'Bank Soal';
    $pageSubtitle = 'Kelola repositori pertanyaan, kunci jawaban, dan kategori soal.';
@endphp

@section('title', 'Bank Soal – Admin DIKSERA')

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

        /* Badges */
        .badge-soft {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-soft-primary { background: #dbeafe; color: #1e40af; }
        .badge-soft-info { background: #e0f2fe; color: #075985; }
        
        /* Key Answer Badge (Circle) */
        .badge-key {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #dcfce7;
            color: #166534;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 1px solid #bbf7d0;
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

        /* Search Input */
        .search-input {
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            font-size: 13px;
            padding-left: 12px;
        }
        .search-input:focus {
            border-color: var(--blue-main);
            box-shadow: 0 0 0 3px var(--blue-soft);
        }
    </style>
@endpush

@section('content')

    <div class="content-card">

        {{-- Header Tools --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            
            {{-- Search Bar --}}
            <form action="" method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm search-input" placeholder="Cari pertanyaan..."
                    style="width: 240px;">
                <button class="btn btn-sm btn-light border" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.bank-soal.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            {{-- Create Button --}}
            <a href="{{ route('admin.bank-soal.create') }}" class="btn btn-sm btn-primary px-3 shadow-sm"
                style="border-radius: 8px;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Soal Baru
            </a>
        </div>

        {{-- Table Content --}}
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Pertanyaan & Opsi</th>
                        <th style="width: 150px;">Kategori</th>
                        <th style="width: 80px;" class="text-center">Kunci</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($soals as $soal)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration + ($soals->firstItem() ? $soals->firstItem() - 1 : 0) }}</td>
                            <td>
                                {{-- Pertanyaan --}}
                                <div class="fw-bold text-dark mb-1" style="max-width: 450px; line-height: 1.4;">
                                    {{ Str::limit($soal->pertanyaan, 80) }}
                                </div>
                                
                                {{-- Preview Opsi Jawaban --}}
                                <div class="text-muted small d-flex flex-wrap gap-2" style="font-size: 11px;">
                                    <span class="d-flex align-items-center gap-1">
                                        <i class="bi bi-list-ul text-secondary"></i> Opsi:
                                    </span>
                                    @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                        @if(isset($soal->opsi_jawaban[$opt]))
                                            <span class="{{ strtolower($soal->kunci_jawaban) == $opt ? 'text-success fw-bold' : '' }}">
                                                {{ strtoupper($opt) }}
                                            </span>
                                            @if(!$loop->last)<span class="text-black-50">|</span>@endif
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-info">
                                    <i class="bi bi-tag me-1"></i> {{ $soal->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <span class="badge-key" title="Kunci Jawaban: {{ strtoupper($soal->kunci_jawaban) }}">
                                        {{ strtoupper($soal->kunci_jawaban) }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.bank-soal.edit', $soal->id) }}"
                                        class="btn btn-icon btn-outline-warning" title="Edit Soal"
                                        data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.bank-soal.delete', $soal->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        {{-- Method DELETE jika route mendukung, jika POST biarkan POST --}}
                                        {{-- @method('DELETE') --}} 
                                        <button type="submit" class="btn btn-icon btn-outline-danger" title="Hapus"
                                            data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-stack display-6 opacity-25"></i>
                                </div>
                                <span class="text-muted small">Belum ada soal ditambahkan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-end">
            @if(method_exists($soals, 'links'))
                {{ $soals->withQueryString()->links('vendor.pagination.diksera') }}
            @endif
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
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Handle Flash Success
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            // Handle Delete Confirmation
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Hapus Soal?',
                        text: "Soal ini akan dihapus permanen dari bank soal.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush