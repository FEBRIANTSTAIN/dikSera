@extends('layouts.app')

@section('title','Pendidikan – DIKSERA')

@section('content')
<div class="container py-3">
    <div class="dash-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Riwayat Pendidikan</h6>
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
        <form action="{{ route('perawat.pendidikan.store') }}" method="POST" enctype="multipart/form-data" class="small mb-3">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Jenjang *</label>
                    <input type="text" name="jenjang" class="form-control form-control-sm" placeholder="D3 / S1 / Profesi">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Institusi *</label>
                    <input type="text" name="nama_institusi" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Akreditasi</label>
                    <input type="text" name="akreditasi" class="form-control form-control-sm" placeholder="A / B / C">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun Lulus</label>
                    <input type="text" name="tahun_lulus" class="form-control form-control-sm" placeholder="2020">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Dokumen</label>
                    <input type="file" name="dokumen" class="form-control form-control-sm">
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-end">
                <button type="submit" class="btn btn-sm btn-primary">
                    + Tambah Pendidikan
                </button>
            </div>
        </form>

        {{-- TABEL LIST --}}
        <div class="table-responsive small">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Jenjang</th>
                        <th>Nama Institusi</th>
                        <th>Akreditasi</th>
                        <th>Tempat</th>
                        <th>Tahun Lulus</th>
                        <th>Dokumen</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendidikan as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                <form action="{{ route('perawat.pendidikan.update',$row->id) }}" method="POST" enctype="multipart/form-data" class="row g-1 align-items-center">
                                    @csrf
                                    <div class="col-12 col-md-2">
                                        <input type="text" name="jenjang" value="{{ $row->jenjang }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="text" name="nama_institusi" value="{{ $row->nama_institusi }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-4 col-md-1">
                                        <input type="text" name="akreditasi" value="{{ $row->akreditasi }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <input type="text" name="tempat" value="{{ $row->tempat }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <input type="text" name="tahun_lulus" value="{{ $row->tahun_lulus }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="d-flex flex-column gap-1">
                                            <input type="file" name="dokumen" class="form-control form-control-sm">
                                            @if($row->dokumen_path)
                                                <a href="{{ asset('storage/'.$row->dokumen_path) }}"
                                                   target="_blank">Lihat</a>
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 mt-1 d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Simpan
                                        </button>
                        </form>
                        <form action="{{ route('perawat.pendidikan.destroy',$row->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Hapus
                            </button>
                        </form>
                                    </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada data pendidikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
