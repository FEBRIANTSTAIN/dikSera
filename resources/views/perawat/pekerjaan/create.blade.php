@extends('layouts.app')

@section('title', 'Tambah Pekerjaan – DIKSERA')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- 1. CSS Choices.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fafc;
            --input-border: #e2e8f0;
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
            /* Penting agar dropdown tidak terpotong */
            overflow: visible;
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

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        /* --- Custom Choices.js Styling agar mirip input lain --- */
        .choices__inner {
            background-color: #fff;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            padding: 6px 16px; /* Adjust padding */
            min-height: 48px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
        }

        .choices.is-focused .choices__inner {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
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
        .btn-submit {
            background-color: var(--primary-blue);
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -1px rgba(37, 99, 235, 0.3);
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

        /* Alert Style */
        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    @php
        // 1. UNIT KERJA (Hardcode)
        $daftarUnitKerja = [
            'INSTALASI GAWAT DARURAT (IGD)', 'INTENSIVE CARE UNIT (ICU)', 'INTENSIVE CARDIO VASKULER CARE UNIT (ICVCU)',
            'PEDIATRI INTENSIVE CARE UNIT (PICU)', 'NEONATUS INTENSIVE CARE UNIT (NICU)', 'IRJ VVIP', 'POLIKLINIK ANAK',
            'POLIKLINIK JANTUNG', 'POLIKLINIK PENYAKIT DALAM', 'POLIKLINIK VCT', 'POLIKLINIK PARU', 'POLIKLINIK TB',
            'POLIKLINIK SYARAF', 'POLIKLINIK PSIKIATRI', 'POLIKLINIK BEDAH', 'POLIKLINIK ORTHOPEDI', 'POLIKLINIK UROLOGI',
            'POLIKLINIK OBGYN', 'POLIKLINIK KULIT DAN KELAMIN', 'MCU', 'IRNA CENDRAWASIH', 'IRNA PERKUTUT',
            'IRNA PUNAI (BEDAH)', 'IRNA KASUARI (PENYAKIT DALAM)', 'IRNA PARKIT (ANAK)', 'IRNA GELATIK (KHUSUS)',
            'IRNA MERAK (OBGYN)', 'NEONATUS', 'MPP', 'DIKLAT', 'INSTALASI BEDAH SENTRAL', 'RUANG PULIH SADAR',
        ];

        // 2. STATUS KEPEGAWAIAN
        $statusList = [
            'ASN - PNS',
            'ASN - PPPK',
            'ASN - PPPK PARUH WAKTU',
            'NON ASN - KARYAWAN TETAP',
            'NON ASN - KONTRAK'
        ];

        // 3. PANGKAT (Hanya untuk ASN)
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
                    <div><h1 class="page-title">Tambah Riwayat Pekerjaan</h1></div>
                    <a href="{{ route('perawat.pekerjaan.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>

                <div class="form-card">
                    @if ($errors->any())
                        <div class="alert alert-danger py-3 px-4 mb-4"><ul class="mb-0 ps-3">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>
                    @endif

                    <form action="{{ route('perawat.pekerjaan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Instansi <span class="required-star">*</span></label>
                                <input type="text" name="nama_instansi" class="form-control" value="{{ old('nama_instansi') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Unit Kerja <span class="required-star">*</span></label>
                                <select name="unit_kerja" id="choices-unit-kerja" class="form-control" required>
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach($daftarUnitKerja as $unit)
                                        <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- STATUS KEPEGAWAIAN --}}
                            <div class="col-md-6">
                                <label class="form-label">Status Kepegawaian <span class="required-star">*</span></label>
                                <select name="status_kepegawaian" id="status-select" class="form-select" required>
                                    <option value="">- Pilih Status -</option>
                                    @foreach($statusList as $status)
                                        <option value="{{ $status }}" {{ old('status_kepegawaian') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- JABATAN (Opsi berubah via JS tergantung Status) --}}
                            <div class="col-md-6">
                                <label class="form-label">Jabatan <span class="required-star">*</span></label>
                                <select name="jabatan" id="jabatan-select" class="form-select" required>
                                    <option value="">- Pilih Status Kepegawaian Terlebih Dahulu -</option>
                                    {{-- Opsi diisi via Javascript --}}
                                </select>
                                {{-- Input hidden untuk menyimpan value old() jabatan saat validasi error --}}
                                <input type="hidden" id="old-jabatan" value="{{ old('jabatan') }}">
                            </div>

                            {{-- PANGKAT (Muncul jika ASN) --}}
                            <div class="col-md-12 d-none" id="pangkat-wrapper">
                                <label class="form-label">Pangkat / Golongan <span class="required-star">*</span></label>
                                <select name="pangkat" id="pangkat-select" class="form-select">
                                    <option value="">- Pilih Pangkat -</option>
                                    @foreach($pangkatList as $pkt)
                                        <option value="{{ $pkt }}" {{ old('pangkat') == $pkt ? 'selected' : '' }}>{{ $pkt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Form Lain --}}
                            <div class="col-md-6">
                                <label class="form-label">Tahun Mulai</label>
                                <input type="number" name="tahun_mulai" class="form-control" value="{{ old('tahun_mulai') }}" placeholder="YYYY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun Selesai</label>
                                <input type="number" name="tahun_selesai" class="form-control" value="{{ old('tahun_selesai') }}" placeholder="YYYY (Kosongkan jika aktif)">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Upload Dokumen / SK</label>
                                <input type="file" name="dokumen" class="form-control">
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn-submit"><i class="bi bi-save2"></i> Simpan Riwayat</button>
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
            // 1. Choices JS untuk Unit Kerja
            new Choices('#choices-unit-kerja', { searchEnabled: true, itemSelectText: '', shouldSort: false, placeholder: true, placeholderValue: 'Pilih Unit Kerja...' });

            // 2. Data Jabatan (Defined in JS for Dynamic Dropdown)
            const jabatanASN = [
                'PERAWAT TERAMPIL', 'PERAWAT MAHIR', 'PERAWAT PENYELIA', 'PERAWAT AHLI PERTAMA',
                'PERAWAT AHLI MUDA', 'PERAWAT AHLI MADYA', 'PERAWAT AHLI UTAMA',
                'BIDAN TERAMPIL', 'BIDAN MAHIR', 'BIDAN PENYELIA', 'BIDAN AHLI PERTAMA',
                'BIDAN AHLI MUDA', 'BIDAN AHLI MADYA', 'BIDAN AHLI UTAMA'
            ];

            const jabatanNonASN = [
                'PERAWAT PELAKSANA', 'BIDAN PELAKSANA'
            ];

            // 3. Logic Dropdown Dependent
            const statusSelect = document.getElementById('status-select');
            const jabatanSelect = document.getElementById('jabatan-select');
            const pangkatWrapper = document.getElementById('pangkat-wrapper');
            const pangkatSelect = document.getElementById('pangkat-select');
            const oldJabatan = document.getElementById('old-jabatan').value;

            function updateForm() {
                const status = statusSelect.value;
                jabatanSelect.innerHTML = '<option value="">- Pilih Jabatan -</option>'; // Reset

                let listJabatan = [];
                let isASN = false;

                // Cek apakah string status mengandung kata "ASN -" (untuk PNS, PPPK, PPPK Paruh Waktu)
                // Tapi tidak boleh mengandung "NON ASN"
                if (status.includes('ASN') && !status.includes('NON')) {
                    listJabatan = jabatanASN;
                    isASN = true;
                } else if (status.includes('NON ASN')) {
                    listJabatan = jabatanNonASN;
                    isASN = false;
                }

                // Populate Jabatan
                listJabatan.forEach(function(jab) {
                    const option = document.createElement('option');
                    option.value = jab;
                    option.text = jab;
                    if(jab === oldJabatan) option.selected = true; // Select old value
                    jabatanSelect.appendChild(option);
                });

                // Show/Hide Pangkat
                if (isASN) {
                    pangkatWrapper.classList.remove('d-none');
                    pangkatSelect.setAttribute('required', 'required');
                } else {
                    pangkatWrapper.classList.add('d-none');
                    pangkatSelect.removeAttribute('required');
                    pangkatSelect.value = ''; // Reset
                }
            }

            // Jalankan saat change dan saat load (untuk old input)
            statusSelect.addEventListener('change', updateForm);
            if(statusSelect.value) updateForm();
        });
    </script>
@endpush
