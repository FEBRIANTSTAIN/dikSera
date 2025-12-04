@extends('layouts.app')

@section('title','Pelatihan – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="dash-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Kursus / Pelatihan</h6>
            <a href="{{ route('perawat.drh') }}" class="btn btn-sm btn-outline-secondary">
                Kembali ke DRH
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM TAMBAH --}}
        <form action="{{ route('perawat.pelatihan.store') }}" method="POST" enctype="multipart/form-data" class="small mb-3">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nama Pelatihan *</label>
                    <input type="text" name="nama_pelatihan" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control form-control-sm">
                </div>
                <div class="col-md2">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Durasi</label>
                    <input type="text" name="durasi" class="form-control form-control-sm" placeholder="3 hari / 32 JP">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dokumen</label>
                    <input type="file" name="dokumen" class="form-control form-control-sm">
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-end">
                <button type="submit" class="btn btn-sm btn-primary">
                    + Tambah Pelatihan
                </button>
            </div>
        </form>

        {{-- TABEL --}}
        <div class="table-responsive small">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelatihan</th>
                        <th>Penyelenggara</th>
                        <th>Tempat</th>
                        <th>Durasi</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Dokumen</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelatihan as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td colspan="7">
                                <form action="{{ route('perawat.pelatihan.update',$row->id) }}" method="POST" enctype="multipart/form-data" class="row g-1 align-items-center">
                                    @csrf
                                    <div class="col-12 col-md-3">
                                        <input type="text" name="nama_pelatihan" value="{{ $row->nama_pelatihan }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="text" name="penyelenggara" value="{{ $row->penyelenggara }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" name="tempat" value="{{ $row->tempat }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" name="durasi" value="{{ $row->durasi }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <input type="date" name="tanggal_mulai" value="{{ $row->tanggal_mulai }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <input type="date" name="tanggal_selesai" value="{{ $row->tanggal_selesai }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="d-flex flex-column gap-1">
                                            <input type="file" name="dokumen" class="form-control form-control-sm">
                                            @if($row->dokumen_path)
                                                <a href="{{ asset('storage/'.$row->dokumen_path) }}" target="_blank">Lihat</a>
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-1 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Simpan
                                        </button>
                                </form>
                                <form action="{{ route('perawat.pelatihan.destroy',$row->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Hapus
                                    </button>
                                </form>
                                    </div>
                            </td>
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                Belum ada data pelatihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
