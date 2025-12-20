@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="mb-1">Dashboard Pewawancara</h2>
            <p class="text-muted">Selamat datang, {{ $pewawancara->nama }}</p>
        </div>
        <div class="col-md-4 text-end">
            {{-- Tombol ini mengarah ke halaman antrian yang baru dibuat --}}
            <a href="{{ route('pewawancara.antrian') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-list-ul me-2"></i> Lihat Daftar Antrian
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8">Total Menunggu</h6>
                            <h2 class="mb-0 display-4 fw-bold">{{ $totalAntrian }}</h2>
                        </div>
                        <i class="fas fa-user-clock fa-3x" style="opacity: 0.3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8">Jadwal Hari Ini</h6>
                            <h2 class="mb-0 display-4 fw-bold">{{ $hariIni }}</h2>
                        </div>
                        <i class="fas fa-calendar-day fa-3x" style="opacity: 0.3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.8">Selesai Dinilai</h6>
                            <h2 class="mb-0 display-4 fw-bold">{{ $selesai }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x" style="opacity: 0.3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Kalender Jadwal Wawancara</h5>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Calendar --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            themeSystem: 'bootstrap',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: @json($jadwalKalender), // Menggunakan variabel jadwalKalender, BUKAN antrian
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            }
        });
        calendar.render();
    });
</script>

<style>
    .fc-event {
        cursor: pointer;
        text-decoration: none;
    }
    .fc-toolbar-title {
        font-size: 1.25rem !important;
    }
</style>
@endsection