@extends('layouts.app')

@section('title', 'Admin - Approval Perpanjangan')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        /* ... CSS SAMA SEPERTI SEBELUMNYA ... */
        :root {
            --primary-blue: #2563eb;
            --text-dark: #0f172a;
            --text-gray: #64748b;
            --bg-light: #f8fafc;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            padding-bottom: 80px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .filter-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        .choices__inner {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .choices__list--single {
            padding: 0;
        }

        .choices {
            margin-bottom: 0;
        }

        .choices__list--dropdown {
            z-index: 1050;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .custom-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 16px 24px;
            text-align: left;
        }

        .custom-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        .data-title {
            font-weight: 600;
            color: var(--text-dark);
            display: block;
        }

        .data-sub {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            cursor: pointer;
            border: 2px solid #cbd5e1;
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge.pending {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }

        .status-badge.info {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        .status-badge.success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #dcfce7;
        }

        .status-badge.danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }

        .btn-action-group {
            display: flex;
            gap: 6px;
        }

        .btn-xs {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .pagination {
            margin-top: 20px;
            justify-content: flex-end;
        }

        @media (max-width: 576px) {
            .pagination {
                justify-content: center;
            }
        }

        .bulk-action-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(150%);
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            z-index: 1060;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 90%;
            max-width: 700px;
            justify-content: space-between;
        }

        .bulk-action-bar.active {
            transform: translateX(-50%) translateY(0);
        }

        .selected-count {
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .bulk-buttons {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        /* Scroll jika tombol banyak */
    </style>
@endpush

@section('content')
    <div class="container py-4 py-md-5">

        <div class="page-header">
            <h1 class="page-title">Approval Perpanjangan</h1>
            <p class="page-subtitle">Manajemen validasi lisensi dan sertifikat perawat.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- FILTER SECTION --}}
        <div class="filter-card">
            <form action="{{ route('admin.pengajuan.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="filter-label">Cari Data</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama atau Email..."
                            value="{{ request('search') }}" style="height: 44px; border-radius: 8px;">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                        <label class="filter-label">Status</label>
                        <select name="status" id="choice-status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="method_selected" {{ request('status') == 'method_selected' ? 'selected' : '' }}>
                                Sedang Ujian</option>
                            <option value="exam_passed" {{ request('status') == 'exam_passed' ? 'selected' : '' }}>Lulus
                                Ujian</option>
                            <option value="interview_scheduled"
                                {{ request('status') == 'interview_scheduled' ? 'selected' : '' }}>Jadwal Wawancara</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="filter-label">Jenis Sertifikat</label>
                        <select name="sertifikat" id="choice-sertifikat">
                            <option value="">Semua Jenis</option>
                            @foreach ($listSertifikat as $sertif)
                                <option value="{{ $sertif }}"
                                    {{ request('sertifikat') == $sertif ? 'selected' : '' }}>{{ $sertif }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                        <label class="filter-label">Ujian</label>
                        <select name="ujian" id="choice-ujian">
                            <option value="">Semua</option>
                            <option value="sudah" {{ request('ujian') == 'sudah' ? 'selected' : '' }}>Sudah Ada Nilai
                            </option>
                            <option value="belum" {{ request('ujian') == 'belum' ? 'selected' : '' }}>Belum Ujian</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold" style="height: 44px;">
                                <i class="bi bi-search"></i> <span class="d-none d-md-inline">Filter</span>
                            </button>
                            @if (request()->anyFilled(['search', 'status', 'sertifikat', 'ujian']))
                                <a href="{{ route('admin.pengajuan.index') }}"
                                    class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                                    style="height: 44px; width: 44px;" title="Reset Filter">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
                            <th>Perawat</th>
                            <th>Sertifikat</th>
                            <th>Status</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $item)
                            <tr>
                                <td class="text-center">
                                    {{-- [PERBAIKAN] Checkbox juga muncul untuk status interview_scheduled --}}
                                    @if (in_array($item->status, ['pending', 'method_selected', 'interview_scheduled']))
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="form-check-input check-item">
                                    @else
                                        <input type="checkbox" disabled class="form-check-input opacity-25">
                                    @endif
                                </td>
                                <td>
                                    <span class="data-title">{{ $item->user->name }}</span>
                                    <span class="data-sub text-muted">{{ $item->user->email }}</span>
                                </td>
                                <td>{{ $item->lisensiLama->nama ?? '-' }}</td>
                                <td>
                                    @if ($item->status == 'pending')
                                        <span class="status-badge pending">Menunggu</span>
                                    @elseif($item->status == 'method_selected')
                                        <span class="status-badge info">Sedang Ujian</span>
                                    @elseif($item->status == 'exam_passed')
                                        <span class="status-badge info">Tunggu Wawancara</span>
                                    @elseif($item->status == 'interview_scheduled')
                                        <span class="status-badge info">Jadwal Wawancara</span>
                                    @elseif($item->status == 'completed')
                                        <span class="status-badge success">Selesai</span>
                                    @elseif($item->status == 'rejected')
                                        <span class="status-badge danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $item->metode == 'pg_only' ? 'Hanya PG' : ($item->metode ? 'PG + Wawancara' : '-') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-action-group">
                                        <a href="{{ route('admin.pengajuan.show', $item->id) }}"
                                            class="btn btn-info btn-xs text-white"><i class="bi bi-eye"></i></a>

                                        @if ($item->status == 'pending')
                                            <form action="{{ route('admin.pengajuan.approve', $item->id) }}"
                                                method="POST">
                                                @csrf <button class="btn btn-success btn-xs"
                                                    onclick="return confirm('Setujui?')"><i
                                                        class="bi bi-check-lg"></i></button>
                                            </form>
                                        @elseif ($item->status == 'method_selected' && $item->user->examResult)
                                            <a href="{{ route('admin.pengajuan.approve_score', $item->id) }}"
                                                class="btn btn-warning btn-xs text-white"
                                                onclick="return confirm('Acc Nilai?')">
                                                <i class="bi bi-check-all"></i>
                                            </a>
                                        @elseif ($item->status == 'interview_scheduled' && $item->jadwalWawancara)
                                            @php $jadwal = $item->jadwalWawancara; @endphp

                                            @if ($jadwal->status == 'pending')
                                                <a href="{{ route('admin.pengajuan_wawancara.approve', $jadwal->id) }}"
                                                    class="btn btn-success btn-xs text-white"
                                                    onclick="return confirm('Setujui jadwal?')">
                                                    <i class="bi bi-calendar-check"></i>
                                                </a>
                                                {{-- Tombol Reject (Sama seperti sebelumnya) --}}
                                                <button type="button" class="btn btn-outline-danger btn-xs"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $jadwal->id }}"><i
                                                        class="bi bi-x-lg"></i></button>
                                                {{-- ... Modal Code (Bisa diletakkan di luar loop jika mau optimasi, tapi di sini biar simpel) ... --}}
                                                <div class="modal fade" id="rejectModal{{ $jadwal->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form
                                                            action="{{ route('admin.pengajuan_wawancara.reject', $jadwal->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title">Tolak Jadwal</h6>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <textarea name="alasan" class="form-control" rows="3" required placeholder="Alasan..."></textarea>
                                                                </div>
                                                                <div class="modal-footer"><button type="submit"
                                                                        class="btn btn-danger btn-sm">Kirim</button></div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @elseif($jadwal->status == 'approved')
                                                <a href="{{ route('admin.pengajuan_wawancara.penilaian', $jadwal->id) }}"
                                                    class="btn btn-primary btn-xs text-white"><i
                                                        class="bi bi-clipboard-data"></i></a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $pengajuan->links() }}
        </div>
    </div>

    {{-- === FLOATING BULK ACTION BAR === --}}
    <div class="bulk-action-bar" id="bulkActionBar">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white text-dark rounded-pill px-3 py-2" id="selectedCountBadge">0</span>
            <span class="selected-count">Item Dipilih</span>
        </div>
        <div class="bulk-buttons">
            {{-- 1. Acc Pending --}}
            <form id="formBulkApprove" action="{{ route('admin.pengajuan.bulk_approve') }}" method="POST"
                class="d-inline">
                @csrf <div id="bulkApproveInputs"></div>
                <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-3"
                    onclick="submitBulk('formBulkApprove', 'Setujui semua pengajuan yang dipilih?')">
                    <i class="bi bi-check-lg"></i> Acc Pending
                </button>
            </form>

            {{-- 2. Acc Nilai --}}
            <form id="formBulkScore" action="{{ route('admin.pengajuan.bulk_approve_score') }}" method="POST"
                class="d-inline">
                @csrf <div id="bulkScoreInputs"></div>
                <button type="button" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-3"
                    onclick="submitBulk('formBulkScore', 'Verifikasi nilai peserta?')">
                    <i class="bi bi-check-all"></i> Acc Nilai
                </button>
            </form>

            {{-- [TAMBAHAN] 3. Acc Jadwal Wawancara --}}
            <form id="formBulkInterview" action="{{ route('admin.pengajuan.bulk_approve_interview') }}" method="POST"
                class="d-inline">
                @csrf <div id="bulkInterviewInputs"></div>
                <button type="button" class="btn btn-info text-white btn-sm fw-bold rounded-pill px-3"
                    onclick="submitBulk('formBulkInterview', 'Setujui semua jadwal wawancara terpilih?')">
                    <i class="bi bi-calendar-check"></i> Acc Jadwal
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const config = {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                placeholder: true,
                allowHTML: true
            };
            new Choices('#choice-status', {
                ...config,
                searchEnabled: false
            });
            new Choices('#choice-sertifikat', config);
            new Choices('#choice-ujian', {
                ...config,
                searchEnabled: false
            });

            const checkAll = document.getElementById('checkAll');
            const checkItems = document.querySelectorAll('.check-item');
            const bulkActionBar = document.getElementById('bulkActionBar');
            const selectedCountBadge = document.getElementById('selectedCountBadge');

            function updateBulkBar() {
                const checked = document.querySelectorAll('.check-item:checked');
                const count = checked.length;
                selectedCountBadge.innerText = count;
                if (count > 0) bulkActionBar.classList.add('active');
                else bulkActionBar.classList.remove('active');
            }

            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    if (!item.disabled) item.checked = this.checked;
                });
                updateBulkBar();
            });

            checkItems.forEach(item => {
                item.addEventListener('change', updateBulkBar);
            });
        });

        function submitBulk(formId, confirmMsg) {
            if (!confirm(confirmMsg)) return;
            const form = document.getElementById(formId);
            // Tentukan container ID berdasarkan Form ID
            let containerId = '';
            if (formId === 'formBulkApprove') containerId = 'bulkApproveInputs';
            else if (formId === 'formBulkScore') containerId = 'bulkScoreInputs';
            else if (formId === 'formBulkInterview') containerId = 'bulkInterviewInputs'; // Tambahan

            const container = document.getElementById(containerId);
            container.innerHTML = '';
            const checkedItems = document.querySelectorAll('.check-item:checked');

            if (checkedItems.length === 0) {
                alert('Pilih setidaknya satu data.');
                return;
            }

            checkedItems.forEach(item => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = item.value;
                container.appendChild(input);
            });
            form.submit();
        }
    </script>
@endpush
