@extends('layouts.app')

@php
    $pageTitle = 'Atur Soal Ujian';
    $pageSubtitle = 'Pilih pertanyaan dari bank soal untuk dimasukkan ke dalam form ujian.';
@endphp

@section('title', 'Kelola Soal – Admin DIKSERA')

@push('styles')
    <style>
        /* Card Container */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 24px;
            display: flex;
            flex-direction: column;
            height: 80vh;
            /* Agar tabel bisa scroll di dalam card */
        }

        /* Search Input */
        .search-input {
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            font-size: 13px;
            padding: 10px 12px;
            padding-left: 35px;
            /* Space for icon */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%239ca3af' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 10px center;
        }

        .search-input:focus {
            border-color: var(--blue-main);
            box-shadow: 0 0 0 3px var(--blue-soft);
        }

        /* Custom Table */
        .table-custom th {
            background-color: #f8fafc;
            color: var(--text-main);
            font-weight: 700;
            font-size: 12px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            vertical-align: middle;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        /* Row Selection Effect */
        .table-custom tbody tr {
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-custom tbody tr.selected {
            background-color: #eff6ff;
            /* Light Blue */
        }

        .table-custom tbody tr.selected td {
            border-bottom-color: #dbeafe;
        }

        /* Checkbox Custom */
        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Badges */
        .badge-soft {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-soft-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-soft-info {
            background: #e0f2fe;
            color: #075985;
        }

        /* Sticky Footer Action */
        .action-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border-soft);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid h-100">
        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
            <div>
                <h4 class="fw-bold mb-1">Atur Soal Ujian</h4>
                <div class="text-muted small">
                    Target Form: <span class="fw-bold text-dark">{{ $form->judul }}</span>
                </div>
            </div>
            <a href="{{ route('admin.form.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                style="border-radius: 8px;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.form.simpan-soal', $form->id) }}" method="POST" class="h-100">
            @csrf

            <div class="content-card">

                {{-- Toolbar: Search & Info --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="position-relative" style="width: 300px;">
                        <input type="text" id="searchInput" class="form-control search-input"
                            placeholder="Cari pertanyaan atau kategori...">
                    </div>

                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2">
                            Total Bank Soal: <strong>{{ $allSoals->count() }}</strong>
                        </span>
                    </div>
                </div>

                {{-- Table Scrollable Area --}}
                <div class="table-responsive flex-grow-1 border rounded-3 custom-scroll">
                    <table class="table table-custom mb-0" id="soalTable">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </th>
                                <th>Pertanyaan</th>
                                <th width="150">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSoals as $soal)
                                @php
                                    $isSelected = in_array($soal->id, $existingSoalIds);
                                @endphp
                                <tr class="{{ $isSelected ? 'selected' : '' }} searchable-row" onclick="toggleRow(this)">
                                    <td class="text-center" onclick="event.stopPropagation()">
                                        <input type="checkbox" name="soal_ids[]" value="{{ $soal->id }}"
                                            class="form-check-input soal-checkbox" {{ $isSelected ? 'checked' : '' }}
                                            onchange="highlightRow(this)">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1" style="line-height: 1.4;">
                                            {{ $soal->pertanyaan }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                                                style="font-size: 10px;">
                                                Kunci: {{ strtoupper($soal->kunci_jawaban) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-soft badge-soft-info">
                                            {{ $soal->kategori }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-6 opacity-25"></i> <br>
                                        Bank soal masih kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Action --}}
                <div class="action-footer">
                    <div class="small text-muted">
                        Terpilih: <strong id="selectedCount" class="text-primary">{{ count($existingSoalIds) }}</strong>
                        soal
                    </div>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-save me-2"></i> Simpan Konfigurasi
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const table = document.getElementById('soalTable');
            const checkboxes = document.querySelectorAll('.soal-checkbox');
            const checkAll = document.getElementById('checkAll');
            const searchInput = document.getElementById('searchInput');
            const selectedCountSpan = document.getElementById('selectedCount');
            const rows = document.querySelectorAll('.searchable-row');

            // 1. Function to Update Counter
            function updateCounter() {
                const checkedBoxes = document.querySelectorAll('.soal-checkbox:checked');
                selectedCountSpan.innerText = checkedBoxes.length;
            }

            // 2. Function to Highlight Row
            window.highlightRow = function(checkbox) {
                const row = checkbox.closest('tr');
                if (checkbox.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
                updateCounter();
            }

            // 3. Click Row to Toggle Checkbox (UX Enhancement)
            window.toggleRow = function(row) {
                const checkbox = row.querySelector('.soal-checkbox');
                checkbox.checked = !checkbox.checked;
                highlightRow(checkbox);
            }

            // 4. Check All Logic
            checkAll.addEventListener('change', function() {
                // Hanya check yang currently visible (hasil search)
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.soal-checkbox');
                        cb.checked = this.checked;
                        highlightRow(cb);
                    }
                });
            });

            // 5. Search Functionality
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Initialize Counter on Load
            updateCounter();
        });
    </script>
@endpush
