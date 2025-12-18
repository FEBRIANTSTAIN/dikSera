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
            background-color: #eff6ff;
            /* Warna soft blue standar */
            color: #1e293b;
            font-weight: 700;
            font-size: 12px;
            border-bottom: 2px solid #dbeafe;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            vertical-align: middle;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
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

        .badge-soft-info {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        /* Key Answer Badge (Circle) */
        .badge-key {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            /* Sedikit kotak agar modern */
            background: #dcfce7;
            color: #15803d;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: 1px solid #86efac;
        }

        /* Action Buttons */
        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        /* Toolbar Styling */
        .toolbar-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="content-card">

        {{-- Header & Toolbar --}}
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Daftar Pertanyaan</h4>
                <p class="text-muted small mb-0">Total {{ $soals->total() }} soal tersedia di bank soal.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.bank-soal.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Soal
                </a>
            </div>
        </div>

        {{-- Toolbar Tools (Search & Excel) --}}
        <div class="toolbar-container mb-4">
            <div class="row g-3 align-items-center">
                {{-- Search --}}
                <div class="col-md-5 col-lg-4">
                    <form action="" method="GET" class="position-relative">
                        <i class="bi bi-search position-absolute text-muted"
                            style="top: 50%; left: 12px; transform: translateY(-50%);"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-5"
                            placeholder="Cari kata kunci pertanyaan..." style="border-radius: 8px;">
                    </form>
                </div>

                {{-- Excel Tools --}}
                <div class="col-md-7 col-lg-8">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        {{-- Tombol Template --}}
                        <button class="btn btn-light border text-secondary" onclick="downloadTemplate()">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> Template
                        </button>

                        {{-- Group Import/Export --}}
                        <div class="btn-group shadow-sm">
                            <button class="btn btn-success text-white" onclick="exportExcel()">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export
                            </button>
                            <button class="btn btn-outline-success bg-white" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="bi bi-upload me-1"></i> Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="table-responsive rounded-3 border">
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
                            <td class="text-center text-muted fw-bold">
                                {{ $loop->iteration + ($soals->firstItem() ? $soals->firstItem() - 1 : 0) }}
                            </td>
                            <td>
                                {{-- Pertanyaan --}}
                                <div class="fw-bold text-dark mb-2" style="font-size: 15px; line-height: 1.5;">
                                    {{ Str::limit($soal->pertanyaan, 100) }}
                                </div>
                                {{-- Preview Opsi Jawaban --}}
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (['a', 'b', 'c', 'd', 'e'] as $opt)
                                        @if (isset($soal->opsi_jawaban[$opt]))
                                            <span
                                                class="badge {{ strtolower($soal->kunci_jawaban) == $opt ? 'bg-success text-white' : 'bg-light text-muted border' }} fw-normal"
                                                style="font-size: 10px;">
                                                {{ strtoupper($opt) }}. {{ Str::limit($soal->opsi_jawaban[$opt], 15) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-info">
                                    {{ $soal->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <div class="badge-key" title="Kunci: {{ strtoupper($soal->kunci_jawaban) }}">
                                        {{ strtoupper($soal->kunci_jawaban) }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.bank-soal.edit', $soal->id) }}"
                                        class="btn btn-icon btn-light border text-warning" title="Edit"
                                        data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.bank-soal.delete', $soal->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-light border text-danger"
                                            title="Hapus" data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="bg-light rounded-circle p-3 mb-3">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-dark fw-bold">Belum ada data soal</h6>
                                    <p class="text-muted small">Silakan tambah manual atau import via Excel.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-end">
            @if (method_exists($soals, 'links'))
                {{ $soals->withQueryString()->links('vendor.pagination.diksera') }}
            @endif
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Import Soal Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-0 d-flex gap-3 align-items-center mb-3">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div class="small line-height-sm">
                            Gunakan template yang disediakan agar proses import berjalan lancar tanpa error.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">1. Download Template</label>
                        <button
                            class="btn btn-light border w-100 text-start d-flex justify-content-between align-items-center"
                            onclick="downloadTemplate()">
                            <span><i class="bi bi-file-earmark-excel text-success me-2"></i> Template_Soal.xlsx</span>
                            <span class="badge bg-secondary">Download</span>
                        </button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase">2. Upload File</label>
                        <input type="file" id="fileExcel" class="form-control" accept=".xlsx, .xls">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="processImport()">Proses Import</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tooltip Init
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            // Flash Success
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            // Delete Confirm
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Soal?',
                        text: "Data tidak dapat dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        // --- 1. DOWNLOAD TEMPLATE ---
        function downloadTemplate() {
            // Data dummy untuk contoh format
            const templateData = [{
                    "pertanyaan": "Ibu kota Indonesia adalah?",
                    "kategori": "Pengetahuan Umum",
                    "opsi_a": "Bandung",
                    "opsi_b": "Jakarta",
                    "opsi_c": "Surabaya",
                    "opsi_d": "Medan",
                    "opsi_e": "Bali",
                    "kunci_jawaban": "b"
                },
                {
                    "pertanyaan": "Rumus kimia air adalah?",
                    "kategori": "Sains",
                    "opsi_a": "H2O",
                    "opsi_b": "CO2",
                    "opsi_c": "O2",
                    "opsi_d": "NaCl",
                    "opsi_e": "N2",
                    "kunci_jawaban": "a"
                }
            ];

            // Buat Worksheet
            const ws = XLSX.utils.json_to_sheet(templateData);

            // Atur lebar kolom agar rapi saat dibuka di Excel
            const wscols = [{
                    wch: 40
                }, // Pertanyaan
                {
                    wch: 20
                }, // Kategori
                {
                    wch: 15
                }, // Opsi A
                {
                    wch: 15
                }, // Opsi B
                {
                    wch: 15
                }, // Opsi C
                {
                    wch: 15
                }, // Opsi D
                {
                    wch: 15
                }, // Opsi E
                {
                    wch: 10
                } // Kunci
            ];
            ws['!cols'] = wscols;

            // Buat Workbook dan Download
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Template Import");
            XLSX.writeFile(wb, 'Template_Soal_Diksera.xlsx');
        }

        // --- 2. EXPORT EXCEL ---
        function exportExcel() {
            const table = document.getElementsByTagName("table")[0];
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Bank Soal"
            });
            XLSX.writeFile(wb, 'Bank_Soal_Export.xlsx');
        }

        // --- 3. IMPORT EXCEL ---
        function processImport() {
            const fileInput = document.getElementById('fileExcel');
            if (!fileInput.files.length) {
                Swal.fire('Oops', 'Silakan pilih file Excel terlebih dahulu.', 'warning');
                return;
            }

            // Tampilkan loading
            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(sheet);

                if (jsonData.length === 0) {
                    Swal.fire('Error', 'File Excel kosong atau format salah.', 'error');
                    return;
                }

                axios.post('{{ route('admin.bank-soal.import-json') }}', jsonData)
                    .then(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Gagal Import', 'Periksa kembali format Excel Anda.', 'error');
                    });
            };
            reader.readAsArrayBuffer(fileInput.files[0]);
        }
    </script>
@endsection
