@extends('layouts.auth', ['title' => 'Login DIKSERA'])

@section('content')
<div class="text-center mb-3">
    <div class="logo-big mx-auto">
        <img src="{{ asset('icon.png') }}" alt="DIKSERA">
    </div>
    <div class="auth-title">Masuk ke DIKSERA</div>
    <div class="auth-subtitle">
        Gunakan akun yang telah terdaftar (admin / perawat / pewawancara).
    </div>
</div>

<form method="POST" action="{{ route('login.process') }}" class="mt-2">
    @csrf

    <div class="mb-2">
        <label class="form-label small text-muted mb-1">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-control form-control-sm form-control-light"
            required
            autofocus>
    </div>

    <div class="mb-2">
        <label class="form-label small text-muted mb-1">Password</label>
        <div class="password-wrapper">
            <input
                id="password-input"
                type="password"
                name="password"
                class="form-control form-control-sm form-control-light"
                required>
            <button type="button" id="toggle-password" class="toggle-password-btn" data-target="password-input">
                Lihat
            </button>
        </div>
    </div>

    <div class="mb-2 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label for="remember" class="form-check-label small text-muted">Ingat saya</label>
    </div>

    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-solid-blue">
            Masuk
        </button>
    </div>
</form>

<div class="bottom-link">
    Belum punya akun perawat?
    <a href="{{ route('register.perawat') }}">Registrasi di sini</a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('password-input');
    const btn   = document.getElementById('toggle-password');

    if (!input || !btn) return;

    btn.addEventListener('click', function () {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        this.textContent = isPassword ? 'Sembunyikan' : 'Lihat';
    });
});
</script>
@endpush
