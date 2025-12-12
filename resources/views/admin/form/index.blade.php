@extends('layouts.app') {{-- Sesuaikan dengan layout utamamu --}}

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark">Manajemen Google Form</h3>
            <a href="{{ route('admin.form.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Buat Form Baru
            </a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Judul Form</th>
                            <th>Jadwal</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td>
                                    <strong>{{ $form->judul }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($form->deskripsi, 50) }}</small>
                                </td>
                                <td>
                                    <small>Mulai: {{ $form->waktu_mulai->format('d M Y, H:i') }}</small><br>
                                    <small>Selesai: {{ $form->waktu_selesai->format('d M Y, H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ ucfirst($form->target_peserta) }}</span>
                                </td>
                                {{-- Kolom Status --}}
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary'; 
                                        if ($form->status == 'publish') {
                                            $badgeClass = 'bg-success'; 
                                        } elseif ($form->status == 'closed') {
                                            $badgeClass = 'bg-danger'; 
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($form->status) }}
                                    </span>
                                </td>

                                {{-- Kolom Aksi --}}
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear"></i> Atur
                                        </button>
                                        <ul class="dropdown-menu">
                                            {{-- Opsi Publish --}}
                                            <li>
                                                <form action="{{ route('admin.form.update-status', $form->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="publish"
                                                        class="dropdown-item text-success">
                                                        <i class="bi bi-check-circle me-2"></i> Publish
                                                    </button>
                                                </form>
                                            </li>

                                            {{-- Opsi Closed --}}
                                            <li>
                                                <form action="{{ route('admin.form.update-status', $form->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="closed"
                                                        class="dropdown-item text-danger">
                                                        <i class="bi bi-x-circle me-2"></i> Close
                                                    </button>
                                                </form>
                                            </li>

                                            {{-- Opsi Draft --}}
                                            <li>
                                                <form action="{{ route('admin.form.update-status', $form->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="draft"
                                                        class="dropdown-item text-secondary">
                                                        <i class="bi bi-pencil-square me-2"></i> Set Draft
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bi bi-pencil me-2"></i> Edit Detail
                                                </a>
                                            </li>
                                            <li>
                                                <form action="#" 
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
