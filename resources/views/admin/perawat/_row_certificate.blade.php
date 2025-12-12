@php
    // Logika Tanggal Expired
    $now = \Carbon\Carbon::now();
    $expired = \Carbon\Carbon::parse($item->tgl_expired);
    $daysLeft = $now->diffInDays($expired, false);

    if ($daysLeft < 0) {
        $badgeClass = 'badge-soft-danger';
        $icon = 'bi-x-circle';
        $text = 'Expired (' . abs($daysLeft) . ' hari)';
    } elseif ($daysLeft <= 90) {
        $badgeClass = 'badge-soft-warning';
        $icon = 'bi-exclamation-circle';
        $text = 'Exp: ' . $daysLeft . ' hari lagi';
    } else {
        $badgeClass = 'badge-soft-success';
        $icon = 'bi-check-circle';
        $text = 'Aktif';
    }

    $tipeDokumen = 'lisensi'; // Default
    // Cek nama dokumen untuk menentukan tipe
    $namaLower = strtolower($item->nama ?? '');
    
    if (str_contains($namaLower, 'str')) {
        $tipeDokumen = 'str';
    } elseif (str_contains($namaLower, 'sip')) {
        $tipeDokumen = 'sip';
    } 
    if (isset($item->jenis)) {
        $tipeDokumen = 'tambahan';
    }
@endphp

<tr>
    <td class="font-monospace text-dark">{{ $item->nomor }}</td>
    <td class="fw-bold text-dark">{{ $item->nama }}</td>
    <td class="text-muted">{{ $item->lembaga }}</td>
    <td>
        <div class="d-flex flex-column" style="font-size: 11px;">
            <span class="text-muted">Mulai: {{ \Carbon\Carbon::parse($item->tgl_terbit)->format('d M Y') }}</span>
            <span class="{{ $daysLeft < 0 ? 'text-danger fw-bold' : 'text-dark' }}">
                Akhir: {{ $expired->format('d M Y') }}
            </span>
        </div>
    </td>
    <td>
        {{-- Status Masa Berlaku --}}
        <span class="badge-soft {{ $badgeClass }} mb-2 d-inline-flex">
            <i class="bi {{ $icon }}"></i> {{ $text }}
        </span>

        <div class="border-top border-light pt-2 mt-1">
            {{-- Label Status Verifikasi --}}
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted" style="font-size: 10px;">Verifikasi:</span>
                <span class="badge-soft badge-soft-secondary">
                    <span class="fw-bold text-uppercase">{{ $item->kelayakan ?? 'pending' }}</span>
                </span>
            </div>

            {{-- Tombol Aksi (Menggunakan Form) --}}
            <div class="verify-actions">
                {{-- Form Layak --}}
                <form action="{{ route('admin.perawat.verifikasi.kelayakan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="tipe" value="{{ $tipeDokumen }}">
                    <input type="hidden" name="kelayakan" value="layak">
                    <button type="submit" class="btn-verify btn-verify-success" title="Set Layak">
                        <i class="bi bi-check-lg"></i>
                    </button>
                </form>

                {{-- Form Tidak Layak --}}
                <form action="{{ route('admin.perawat.verifikasi.kelayakan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="tipe" value="{{ $tipeDokumen }}">
                    <input type="hidden" name="kelayakan" value="tidak_layak">
                    <button type="submit" class="btn-verify btn-verify-danger" title="Set Tidak Layak">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>

                {{-- Form Pending/Reset --}}
                <form action="{{ route('admin.perawat.verifikasi.kelayakan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="tipe" value="{{ $tipeDokumen }}">
                    <input type="hidden" name="kelayakan" value="pending">
                    <button type="submit" class="btn-verify btn-verify-secondary" title="Reset ke Pending">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </form>
            </div>
        </div>
    </td>
    <td class="text-end">
        @if ($item->file_path)
            <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="btn-file">
                <i class="bi bi-file-earmark-pdf text-danger"></i> Lihat
            </a>
        @else
            <span class="text-muted small opacity-50">Kosong</span>
        @endif
    </td>
</tr>