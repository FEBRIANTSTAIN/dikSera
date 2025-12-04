@extends('layouts.app')

@section('title','Data Perawat – Admin DIKSERA')

@section('content')
<div class="dash-card p-3">

    <h5 class="mb-3">Data Seluruh Perawat</h5>

    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:45px;">No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th style="width:140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perawat as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->profile->nik ?? '—' }}</td>
                        <td>{{ $p->profile->no_hp ?? '—' }}</td>
                        <td>{{ $p->profile->alamat ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.perawat.show', $p->id) }}"
                               class="btn btn-sm btn-primary">
                                Detail DRH
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada data perawat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
