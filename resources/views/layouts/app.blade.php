<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'DIKSERA' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    {{-- Fonts, Bootstrap & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --blue-main: #1d4ed8;
            --blue-soft: #e0edff;
            --blue-soft-2: #f3f6ff;
            --border-soft: #d0d7ee;
            --text-main: #111827;
            --text-muted: #6b7280;
            --sidebar-width: 260px;
            --sidebar-width-collapsed: 80px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #e0ebff 0, #f9fbff 45%, #ffffff 100%);
            color: var(--text-main);
            overflow-x: hidden; /* Mencegah scroll horizontal body */
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            gap: 16px;
            padding: 16px;
            transition: all 0.3s ease;
        }

        /* --- SIDEBAR CSS --- */
        .app-sidebar {
            width: var(--sidebar-width);
            border-radius: 24px;
            background: #ffffff;
            border: 1px solid var(--border-soft);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(148, 163, 184, 0.15);
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            /* PENTING: Visible agar tombol bulat bisa "nongol" keluar */
            overflow: visible;
            position: sticky;
            top: 16px;
            height: calc(100vh - 32px);
            z-index: 100;
        }

        /* State: Collapsed (Tertutup) */
        .app-shell.is-collapsed .app-sidebar {
            width: var(--sidebar-width-collapsed);
            padding: 18px 12px;
        }

        /* --- TOMBOL TOGGLE MENGAMBANG (FLOATING) --- */
        .sidebar-toggle-btn {
            position: absolute;
            right: -16px; /* Keluar setengah dari sidebar */
            top: 36px;    /* Posisi sejajar dengan Logo */

            width: 32px;
            height: 32px;

            background: #ffffff;
            color: var(--blue-main);
            border: 1px solid #d1d5db;
            border-radius: 50%; /* Bulat sempurna */

            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 101; /* Paling atas */

            /* Efek Timbul (Shadow) */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Efek Hover tombol */
        .sidebar-toggle-btn:hover {
            transform: scale(1.15); /* Membesar saat di hover */
            border-color: var(--blue-main);
            box-shadow: 0 0 0 4px var(--blue-soft); /* Ring effect */
        }

        /* Icon Panah Berputar */
        .sidebar-toggle-btn i {
            transition: transform 0.4s;
            font-size: 14px;
            font-weight: 700; /* Bold icon */
        }

        .app-shell.is-collapsed .sidebar-toggle-btn i {
            transform: rotate(180deg); /* Memutar panah */
        }

        /* --- LOGO & BRAND --- */
        .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            min-width: max-content;
            padding-left: 4px; /* Sedikit geser kanan */
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: radial-gradient(circle at 20% 0, #eff6ff, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- MENYEMBUNYIKAN TEKS SAAT COLLAPSED --- */
        .brand-info,
        .link-text,
        .nav-section-title,
        .sidebar-footer {
            opacity: 1;
            transition: opacity 0.2s ease, width 0.2s;
            white-space: nowrap;
        }

        .app-shell.is-collapsed .brand-info,
        .app-shell.is-collapsed .link-text,
        .app-shell.is-collapsed .nav-section-title,
        .app-shell.is-collapsed .sidebar-footer {
            opacity: 0;
            width: 0;
            display: none; /* Hilangkan layout sepenuhnya */
        }

        .brand-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-main);
        }

        .brand-caption {
            font-size: 10px;
            color: var(--text-muted);
            max-width: 140px;
            line-height: 1.2;
        }

        /* --- NAVIGATION LINKS --- */
        .nav-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #9ca3af;
            margin-top: 6px;
            margin-bottom: 4px;
            padding-left: 12px;
        }

        .nav-linkx {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 16px;
            color: #4b5563;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-linkx i {
            font-size: 18px;
            min-width: 24px; /* Lebar tetap icon */
            display: flex;
            justify-content: center;
            color: #6b7280;
            transition: color 0.2s;
        }

        /* Hover & Active States */
        .nav-linkx:hover:not(.active) {
            background: #f3f6ff;
            color: #1d4ed8;
        }
        .nav-linkx:hover:not(.active) i {
            color: #1d4ed8;
        }

        .nav-linkx.active {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        .nav-linkx.active i {
            color: #ffffff;
        }

        /* Posisi Link saat Collapsed */
        .app-shell.is-collapsed .nav-linkx {
            justify-content: center; /* Icon ke tengah */
            padding: 10px 0;
        }

        .sidebar-footer {
            margin-top: auto;
            font-size: 11px;
            color: var(--text-muted);
            padding-left: 12px;
        }

        /* --- MAIN CONTENT --- */
        .app-main {
            flex: 1;
            /* width: 100%; agar mengisi sisa ruang */
            border-radius: 24px;
            background: #ffffff;
            border: 1px solid var(--border-soft);
            box-shadow: 0 20px 55px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(148, 163, 184, 0.16);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: margin-left 0.3s;
            overflow: hidden; /* Mencegah konten meluber */
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-toggle {
            cursor: pointer;
            font-size: 20px;
            color: var(--text-muted);
            margin-right: 5px;
            display: none; /* Hidden by default, show on mobile */
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .user-pill {
            font-size: 11px;
            border-radius: 999px;
            padding: 7px 12px;
            background: #f3f6ff;
            border: 1px solid #d1ddff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #22c55e;
        }

        .btn-logout {
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 11px;
        }

        .main-body {
            flex: 1;
            border-radius: 18px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 18px 18px;
            overflow-x: auto;
        }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 900px) {
            .app-shell {
                flex-direction: column;
                padding: 10px;
            }

            .app-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                align-items: center;
                gap: 10px;
                padding: 12px;
                overflow-x: auto;
                overflow-y: hidden;
            }

            /* Sembunyikan tombol floating di HP */
            .sidebar-toggle-btn {
                display: none;
            }

            /* Header toggle (hamburger) muncul di HP */
            .header-toggle {
                display: block;
            }

            /* Paksa tampilan penuh di HP walaupun state collapsed */
            .app-shell.is-collapsed .app-sidebar {
                width: 100%;
            }
            .app-shell.is-collapsed .brand-info,
            .app-shell.is-collapsed .link-text {
                display: block;
                width: auto;
                opacity: 1;
            }

            .nav-section-title, .sidebar-footer {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="app-shell" id="appShell">

        {{-- SIDEBAR --}}
        <aside class="app-sidebar">
            <div class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                <i class="bi bi-chevron-left"></i>
            </div>

            <div>
                <div class="brand-row mb-2">
                    <div class="brand-logo">
                        <img src="{{ asset('icon.png') }}" alt="Logo">
                    </div>
                    <div class="brand-info">
                        <div class="brand-name">DIKSERA</div>
                        <div class="brand-caption">
                            Digitalisasi Kompetensi
                        </div>
                    </div>
                </div>
                @if (Auth::check() && Auth::user()->role === 'perawat')
                {{-- UMUM --}}
                <div class="nav-section-title">Umum</div>

                <a href="{{ route('dashboard') }}" class="nav-linkx {{ isset($menu) && $menu === 'dashboard' ? 'active' : '' }}" title="Dashboard">
                    <i class="bi bi-grid-fill"></i>
                    <span class="link-text">Dashboard</span>
                </a>

                {{-- MASTER & PROSES --}}
                <div class="nav-section-title">Master & Proses</div>

                <a href="{{ route('perawat.drh') }}" class="nav-linkx {{ request()->routeIs('perawat.drh') ? 'active' : '' }}" title="DRH & Profil">
                    <i class="bi bi-person-vcard-fill"></i>
                    <span class="link-text">DRH & Profil</span>
                </a>

                <a href="{{ route('perawat.pelatihan.index') }}" class="nav-linkx {{ request()->routeIs('perawat.pelatihan.*') ? 'active' : '' }}" title="Pelatihan">
                    <i class="bi bi-award-fill"></i>
                    <span class="link-text">Pelatihan</span>
                </a>

                <a href="{{ route('perawat.pekerjaan.index') }}" class="nav-linkx {{ request()->routeIs('perawat.pekerjaan.*') ? 'active' : '' }}" title="Riwayat Pekerjaan">
                    <i class="bi bi-briefcase-fill"></i>
                    <span class="link-text">Riwayat Pekerjaan</span>
                </a>

                <a href="{{ route('perawat.keluarga.index') }}" class="nav-linkx {{ request()->routeIs('perawat.keluarga.*') ? 'active' : '' }}" title="Data Keluarga">
                    <i class="bi bi-people-fill"></i>
                    <span class="link-text">Data Keluarga</span>
                </a>

                <a href="{{ route('perawat.organisasi.index') }}" class="nav-linkx {{ request()->routeIs('perawat.organisasi.*') ? 'active' : '' }}" title="Organisasi">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span class="link-text">Organisasi</span>
                </a>

                <a href="{{ route('perawat.tandajasa.index') }}" class="nav-linkx {{ request()->routeIs('perawat.tandajasa.*') ? 'active' : '' }}" title="Tanda Jasa">
                    <i class="bi bi-star-fill"></i>
                    <span class="link-text">Tanda Jasa</span>
                </a>

                <a href="#" class="nav-linkx" title="Dokumen">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span class="link-text">Dokumen & Lisensi</span>
                </a>

                <a href="#" class="nav-linkx" title="Ujian">
                    <i class="bi bi-pencil-square"></i>
                    <span class="link-text">Bank Soal</span>
                </a>

                {{-- LAINNYA --}}
                <div class="nav-section-title">Lainnya</div>
                <a href="#" class="nav-linkx" title="Pengaturan">
                    <i class="bi bi-gear-fill"></i>
                    <span class="link-text">Pengaturan</span>
                </a>
                @endif

                {{-- ADMIN PANEL --}}
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <div class="nav-section-title">Admin Panel</div>

                    <a href="{{ route('dashboard.admin') }}"
                        class="nav-linkx {{ isset($menu) && $menu === 'admin' ? 'active' : '' }}">
                        <span class="dot"></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.perawat.index') }}"
                        class="nav-linkx {{ request()->routeIs('admin.perawat.*') ? 'active' : '' }}">
                        <span class="dot"></span>
                        <span>Kelola Data Perawat</span>
                    </a>
                @endif

            </div>

            <div class="sidebar-footer">
                &copy; {{ date('Y') }} DIKSERA<br>
                <span>Komite Keperawatan</span>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="app-main">
            <div class="main-header">
                <div>
                    <div class="page-title">
                        <i class="bi bi-list header-toggle" onclick="toggleSidebar()"></i>
                        {{ $pageTitle ?? 'Dashboard' }}
                    </div>
                    @isset($pageSubtitle)
                        <div class="page-subtitle ms-1">
                            {{ $pageSubtitle }}
                        </div>
                    @endisset
                </div>

                @php($user = \Illuminate\Support\Facades\Auth::user())
                @if ($user)
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-pill">
                            <span class="user-dot"></span>
                            <span style="font-weight:500;">{{ $user->name }}</span>
                            <span class="text-muted" style="font-size:10px;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-logout">
                                Logout
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="main-body">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire(@json(session('swal')));
            });
        </script>
    @endif

    <script>
        // Logic Sidebar Toggle & Save State
        const appShell = document.getElementById('appShell');
        const STORAGE_KEY = 'diksera_sidebar_state';

        // 1. Load state saved
        const savedState = localStorage.getItem(STORAGE_KEY);
        if (savedState === 'collapsed') {
            appShell.classList.add('is-collapsed');
        }

        // 2. Toggle Function
        function toggleSidebar() {
            appShell.classList.toggle('is-collapsed');

            // Simpan ke local storage
            if (appShell.classList.contains('is-collapsed')) {
                localStorage.setItem(STORAGE_KEY, 'collapsed');
            } else {
                localStorage.setItem(STORAGE_KEY, 'expanded');
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
