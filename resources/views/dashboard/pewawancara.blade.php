@extends('layouts.app')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<div class="container-fluid px-4">

{{-- ===== PRIORITY LIST ===== --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Daftar Wawancara (Prioritas)</h5>

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Urgent</th>
                    <th>Waktu</th>
                    <th>Peserta</th>
                    <th>Kompetensi</th>
                    <th>Sikap</th>
                    <th>Pengetahuan</th>
                    <th>Keputusan</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            @foreach($antrian as $j)
                @php
                    $isOverdue = $j->waktu_wawancara < now();
                    $isToday = $j->waktu_wawancara->isToday();
                @endphp

                <tr id="row-{{ $j->id }}">
                    <td>
                        @if($isOverdue)
                            🔴
                        @elseif($isToday)
                            🟠
                        @else
                            🟢
                        @endif
                    </td>

                    <td>{{ $j->waktu_wawancara->format('d M H:i') }}</td>
                    <td>{{ $j->pengajuan->user->name }}</td>

                    <td><input type="number" class="form-control form-control-sm" id="k{{ $j->id }}"></td>
                    <td><input type="number" class="form-control form-control-sm" id="s{{ $j->id }}"></td>
                    <td><input type="number" class="form-control form-control-sm" id="p{{ $j->id }}"></td>

                    <td>
                        <select class="form-select form-select-sm" id="d{{ $j->id }}">
                            <option value="">-</option>
                            <option value="lulus">Lulus</option>
                            <option value="tidak_lulus">Tidak</option>
                        </select>
                    </td>

                    <td>
                        <button class="btn btn-sm btn-success"
                            onclick="submitNilai({{ $j->id }})">
                            ✔
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== RIWAYAT SINGKAT ===== --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold">Riwayat Terakhir</h6>
        <ul class="list-group">
            @foreach($riwayat as $r)
                <li class="list-group-item">
                    ✔ {{ $r->pengajuan->user->name }}
                    <small class="text-muted float-end">
                        {{ $r->updated_at->diffForHumans() }}
                    </small>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- ===== KALENDER ===== --}}
<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

</div>

<script>
function submitNilai(id) {
    axios.post(`/pewawancara/${id}/quick-store`, {
        kompetensi: document.getElementById('k'+id).value,
        sikap: document.getElementById('s'+id).value,
        pengetahuan: document.getElementById('p'+id).value,
        keputusan: document.getElementById('d'+id).value,
    }).then(() => {
        document.getElementById('row-'+id).remove();
    });
}

document.addEventListener('DOMContentLoaded', function () {
    new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'listWeek',
        events: @json($events)
    }).render();
});
</script>
@endsection
