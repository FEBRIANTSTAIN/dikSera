@extends('layouts.app')

@section('title', 'Edit Pekerjaan – DIKSERA')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- 1. CSS Choices.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        :root {
            --primary-blue: #2563eb;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fafc;
            --input-border: #e2e8f0;
            --accent-orange: #f59e0b; /* Warna pembeda untuk konteks Edit */
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        /* --- Header --- */
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* --- Form Card --- */
        .form-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            padding: 40px;
            overflow: visible; /* Penting untuk dropdown */
        }

        /* --- Inputs --- */
        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
        }

        .required-star {
            color: #ef4444;
        }

        .form-control {
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            color: var(--text-dark);
            background-color: #fff;
            transition: all 0.2s ease;
        }

        /* Focus menjadi Orange sesuai tema Edit */
        .form-control:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
            outline: none;
        }

        /* --- Custom Choices.js Styling (Tema Edit - Orange) --- */
        .choices__inner {
            background-color: #fff;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 6px 16px;
            min-height: 48px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
        }

        /* Warna focus disesuaikan dengan tema Edit (Orange) */
        .choices.is-focused .choices__inner {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .choices__list--dropdown {
            border: 1px solid var(--input-border);
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 5px;
        }

        .choices__input {
            background-color: transparent;
            margin-bottom: 0;
        }

        /* --- Buttons --- */
        .btn-submit-edit {
            background-color: var(--accent-orange);
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.25);
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit-edit:hover {
            background-color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -1px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-back {
            background: white;
            border: 1px solid #e2e8f0;
            color: var(--text-gray);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
            border-color: #cbd5e1;
        }

        /* --- File Preview Style --- */
        .file-preview-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 10px 15px;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .file-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .file-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    @php
        // DATA UNIT KERJA
        $daftarUnitKerja = [
            'INSTALASI GAWAT DARURAT (IGD)', 'INTENSIVE CARE UNIT (ICU)', 'INTENSIVE CARDIO VASKULER CARE UNIT (ICVCU)',
            'PEDIATRI INTENSIVE CARE UNIT (PICU)', 'NEONATUS INTENSIVE CARE UNIT (NICU)', 'IRJ VVIP', 'POLIKLINIK ANAK',
            'POLIKLINIK JANTUNG', 'POLIKLINIK PENYAKIT DALAM', 'POLIKLINIK VCT', 'POLIKLINIK PARU', 'POLIKLINIK TB',
            'POLIKLINIK SYARAF', 'POLIKLINIK PSIKIATRI', 'POLIKLINIK BEDAH', 'POLIKLINIK ORTHOPEDI', 'POLIKLINIK UROLOGI',
            'POLIKLINIK OBGYN', 'POLIKLINIK KULIT DAN KELAMIN', 'MCU', 'IRNA CENDRAWASIH', 'IRNA PERKUTUT',
            'IRNA PUNAI (BEDAH)', 'IRNA KASUARI (PENYAKIT DALAM)', 'IRNA PARKIT (ANAK)', 'IRNA GELATIK (KHUSUS)',
            'IRNA MERAK (OBGYN)', 'NEONATUS', 'MPP', 'DIKLAT', 'INSTALASI BEDAH SENTRAL', 'RUANG PULIH SADAR',
        ];

        // STATUS
        $statusList = [
            'ASN - PNS', 'ASN - PPPK', 'ASN - PPPK PARUH WAKTU', 'NON ASN - KARYAWAN TETAP', 'NON ASN - KONTRAK'
        ];

        // PANGKAT
        $pangkatList = [
            'IIa: Pengatur Muda', 'IIb: Pengatur Muda Tingkat I', 'IIc: Pengatur', 'IId: Pengatur Tingkat I',
            'IIIa: Penata Muda', 'IIIb: Penata Muda Tingkat I', 'IIIc: Penata', 'IIId: Penata Tingkat I',
            'IVa: Pembina', 'IVb: Pembina Tingkat I', 'IVc: Pembina Utama Muda', 'IVd: Pembina Utama Madya', 'IVe: Pembina Utama'
        ];
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <div><h1 class="page-title">Edit Riwayat Pekerjaan</h1></div>
                    <a href="{{ route('perawat.pekerjaan.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>

                <div class="form-card">
                    @if ($errors->any())
                        <div class="alert alert-danger py-3 px-4 mb-4"><ul class="mb-0 ps-3">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>
                    @endif

                    <form action="{{ route('perawat.pekerjaan.update', $pekerjaan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Instansi <span class="required-star">*</span></label>
                                <input type="text" name="nama_instansi" class="form-control" value="{{ old('nama_instansi', $pekerjaan->nama_instansi) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Unit Kerja <span class="required-star">*</span></label>
                                <select name="unit_kerja" id="choices-unit-kerja" class="form-control" required>
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach($daftarUnitKerja as $unit)
                                        <option value="{{ $unit }}" {{ old('unit_kerja', $pekerjaan->unit_kerja) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- STATUS KEPEGAWAIAN (Edit) --}}
                            {{-- Pastikan di Model PerawatPekerjaan sudah ada 'status_kepegawaian' --}}
                            <div class="col-md-6">
                                <label class="form-label">Status Kepegawaian <span class="required-star">*</span></label>
                                <select name="status_kepegawaian" id="status-select" class="form-select" required>
                                    <option value="">- Pilih Status -</option>
                                    @foreach($statusList as $status)
                                        {{-- Gunakan data dari database $pekerjaan->status_kepegawaian (sesuaikan nama kolomnya) --}}
                                        <option value="{{ $status }}" {{ old('status_kepegawaian', $pekerjaan->status_kepegawaian ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- JABATAN (Dynamic) --}}
                            <div class="col-md-6">
                                <label class="form-label">Jabatan <span class="required-star">*</span></label>
                                <select name="jabatan" id="jabatan-select" class="form-select" required>
                                    <option value="">- Pilih Status Terlebih Dahulu -</option>
                                </select>
                                {{-- Hidden untuk simpan value lama --}}
                                <input type="hidden" id="old-jabatan" value="{{ old('jabatan', $pekerjaan->jabatan) }}">
                            </div>

                            {{-- PANGKAT (Dynamic Show) --}}
                            <div class="col-md-12 d-none" id="pangkat-wrapper">
                                <label class="form-label">Pangkat / Golongan <span class="required-star">*</span></label>
                                <select name="pangkat" id="pangkat-select" class="form-select">
                                    <option value="">- Pilih Pangkat -</option>
                                    @foreach($pangkatList as $pkt)
                                        <option value="{{ $pkt }}" {{ old('pangkat', $pekerjaan->pangkat) == $pkt ? 'selected' : '' }}>{{ $pkt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sisa Form --}}
                            <div class="col-md-6">
                                <label class="form-label">Tahun Mulai</label>
                                <input type="number" name="tahun_mulai" class="form-control" value="{{ old('tahun_mulai', $pekerjaan->tahun_mulai) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun Selesai</label>
                                <input type="number" name="tahun_selesai" class="form-control" value="{{ old('tahun_selesai', $pekerjaan->tahun_selesai) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $pekerjaan->keterangan) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Update Dokumen / SK</label>
                                <input type="file" name="dokumen" class="form-control">
                                @if ($pekerjaan->dokumen_path)
                                    <div class="mt-2 text-small text-muted">File saat ini: <a href="{{ asset('storage/'.$pekerjaan->dokumen_path) }}" target="_blank">Lihat</a></div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn-submit-edit"><i class="bi bi-check-lg"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#choices-unit-kerja', { searchEnabled: true, itemSelectText: '', shouldSort: false, placeholder: true, placeholderValue: 'Pilih Unit Kerja...' });

            const jabatanASN = [
                'PERAWAT TERAMPIL', 'PERAWAT MAHIR', 'PERAWAT PENYELIA', 'PERAWAT AHLI PERTAMA',
                'PERAWAT AHLI MUDA', 'PERAWAT AHLI MADYA', 'PERAWAT AHLI UTAMA',
                'BIDAN TERAMPIL', 'BIDAN MAHIR', 'BIDAN PENYELIA', 'BIDAN AHLI PERTAMA',
                'BIDAN AHLI MUDA', 'BIDAN AHLI MADYA', 'BIDAN AHLI UTAMA'
            ];
            const jabatanNonASN = ['PERAWAT PELAKSANA', 'BIDAN PELAKSANA'];

            const statusSelect = document.getElementById('status-select');
            const jabatanSelect = document.getElementById('jabatan-select');
            const pangkatWrapper = document.getElementById('pangkat-wrapper');
            const pangkatSelect = document.getElementById('pangkat-select');
            const oldJabatan = document.getElementById('old-jabatan').value;

            function updateForm() {
                const status = statusSelect.value;
                jabatanSelect.innerHTML = '<option value="">- Pilih Jabatan -</option>';

                let listJabatan = [];
                let isASN = false;

                if (status.includes('ASN') && !status.includes('NON')) {
                    listJabatan = jabatanASN;
                    isASN = true;
                } else if (status.includes('NON ASN')) {
                    listJabatan = jabatanNonASN;
                    isASN = false;
                }

                listJabatan.forEach(function(jab) {
                    const option = document.createElement('option');
                    option.value = jab;
                    option.text = jab;
                    if(jab === oldJabatan) option.selected = true;
                    jabatanSelect.appendChild(option);
                });

                if (isASN) {
                    pangkatWrapper.classList.remove('d-none');
                    pangkatSelect.setAttribute('required', 'required');
                } else {
                    pangkatWrapper.classList.add('d-none');
                    pangkatSelect.removeAttribute('required');
                }
            }

            statusSelect.addEventListener('change', updateForm);
            // Jalankan saat load agar data terisi (terutama saat Edit)
            if(statusSelect.value) updateForm();
        });
    </script>
@endpush
