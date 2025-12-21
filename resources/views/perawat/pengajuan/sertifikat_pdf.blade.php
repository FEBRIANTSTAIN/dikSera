<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $lisensi->nomor }}</title>
    <style>
        @page { margin: 0; size: 297mm 210mm; }
        body { margin: 0; padding: 0; font-family: 'Helvetica', 'Arial', sans-serif; background: #fff; }

        .main-layout { width: 100%; height: 100%; border-collapse: collapse; }

        /* --- SIDEBAR (Kiri) --- */
        .td-sidebar {
            width: 28%;
            background-color: #1565c0;
            color: white;
            vertical-align: top;
            text-align: center;
            padding: 40px 10px;
            position: relative;
        }

        .circle-decor {
            position: absolute; top: -50px; left: -50px;
            width: 150px; height: 150px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .side-logo { width: 100px; height: auto; margin-bottom: 20px; }

        .side-title {
            font-size: 9pt; text-transform: uppercase;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            padding-bottom: 10px; margin: 0 auto 40px auto;
            width: 80%; line-height: 1.4;
        }

        /* Frame Foto */
        .photo-frame {
            width: 120px; height: 160px;
            border: 2px solid rgba(255,255,255,0.6);
            border-radius: 8px;
            margin: 0 auto 40px auto;
            background: #fff;
            overflow: hidden;
            position: relative;
        }

        /* Agar gambar mengisi penuh frame */
        .photo-frame img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* Tampilan jika foto kosong */
        .no-photo {
            width: 100%; height: 100%;
            display: flex; /* Flex tidak jalan sempurna di DomPDF untuk alignment, tapi ok untuk display */
            text-align: center;
            background: rgba(255,255,255,0.2);
            color: #1565c0;
            font-weight: bold; font-size: 8pt;
            padding-top: 70px; /* Hack vertical align manual karena flexbox limit di PDF */
            box-sizing: border-box;
        }

        .badge-box {
            width: 90px; height: 90px;
            border: 2px solid #fff;
            border-radius: 10px;
            margin: 0 auto;
            background-color: rgba(13, 71, 161, 0.5);
            text-align: center;
            padding-top: 15px; box-sizing: border-box;
        }
        .badge-level { font-size: 8pt; display: block; margin-bottom: 5px; color: #bbdefb; }
        .badge-value { font-size: 22pt; font-weight: bold; display: block; line-height: 1; }

        /* --- CONTENT (Kanan) --- */
        .td-content {
            width: 72%;
            vertical-align: top;
            padding: 40px 50px;
            position: relative;
            background-color: #fff;
        }

        .watermark {
            position: absolute; right: -20px; bottom: -20px;
            width: 400px; opacity: 0.05; z-index: 0;
        }

        .header-table { width: 100%; margin-bottom: 20px; position: relative; z-index: 2; }
        .header-left h2 { margin: 0; font-size: 14pt; color: #1565c0; text-transform: uppercase; }
        .header-left p { margin: 5px 0 0 0; font-size: 8pt; color: #546e7a; }
        .header-right { text-align: right; vertical-align: top; }

        .cert-number {
            font-family: 'Courier New', monospace; font-size: 10pt;
            color: #1565c0; background: #e3f2fd;
            padding: 5px 10px; border-radius: 4px; display: inline-block;
        }

        .main-title {
            font-size: 30pt; font-weight: 800; color: #0d47a1;
            margin: 10px 0 0 0; text-transform: uppercase; position: relative; z-index: 2;
        }
        .main-title span { color: #2196f3; }
        .subtitle {
            font-size: 11pt; color: #1976d2; margin-bottom: 25px; position: relative; z-index: 2;
        }

        .candidate-box {
            border-left: 5px solid #2979ff; padding-left: 15px; margin-bottom: 25px; position: relative; z-index: 2;
        }
        .candidate-name { font-size: 22pt; font-weight: bold; color: #01579b; margin: 0; text-transform: uppercase; }
        .candidate-id { font-size: 11pt; color: #0277bd; margin-top: 5px; }

        .desc-box {
            background-color: #f1f8ff; border: 1px solid #bbdefb; border-radius: 8px;
            padding: 20px; margin-bottom: 20px; color: #37474f; font-size: 10pt; line-height: 1.5;
            position: relative; z-index: 2;
        }
        .highlight { color: #01579b; font-weight: bold; text-decoration: underline; }

        .event-info {
            margin-top: 15px; padding-top: 10px; border-top: 1px dashed #90caf9;
            font-size: 9pt; color: #455a64;
        }

        .footer-table { width: 100%; margin-top: 30px; position: relative; z-index: 2; }
        .footer-spacer { width: 55%; }
        .footer-sig { width: 45%; text-align: center; }

        .sig-place-date { font-size: 10pt; color: #1565c0; margin-bottom: 5px; }
        .sig-title { font-weight: bold; font-size: 10pt; color: #0d47a1; margin-bottom: 50px; }
        .sig-name { font-weight: bold; color: #0d47a1; border-top: 2px solid #1565c0; padding-top: 5px; display: inline-block; min-width: 200px; }
        .sig-nip { font-size: 9pt; color: #1976d2; margin-top: 2px; }
    </style>
</head>
<body>

    <table class="main-layout">
        <tr>
            <td class="td-sidebar">
                <div class="circle-decor"></div>

                <img src="https://rsudslg.kedirikab.go.id/asset_compro/img/logo/Logo.png" class="side-logo" alt="Logo">

                <div class="side-title">
                    PEMERINTAH KABUPATEN KEDIRI<br>
                    DINAS KESEHATAN<br>
                    UOBK RSUD SLG
                </div>

                <div class="photo-frame">
                    @php
                        $fotoTampil = false;
                        $pathFoto = '';

                        // Cek apakah profile ada dan kolom foto_3x4 terisi
                        if(isset($profile) && !empty($profile->foto_3x4)) {
                            // Cek path storage
                            $storagePath = storage_path('app/public/' . $profile->foto_3x4);

                            if(file_exists($storagePath)) {
                                $pathFoto = $storagePath;
                                $fotoTampil = true;
                            }
                        }
                    @endphp

                    @if($fotoTampil)
                        <img src="{{ $pathFoto }}" alt="Foto 3x4">
                    @else
                        <div class="no-photo">FOTO 3x4</div>
                    @endif
                </div>
                <div class="badge-box">
                    <span class="badge-level">LEVEL</span>
                    <span class="badge-value">{{ $lisensi->kfk ?? 'PK -' }}</span>
                </div>
            </td>

            <td class="td-content">
                <img src="https://rsudslg.kedirikab.go.id/asset_compro/img/logo/Logo.png" class="watermark" alt="Watermark">

                <table class="header-table">
                    <tr>
                        <td class="header-left">
                            <h2>SERTIFIKASI PROFESI</h2>
                            <p>Jl. Galuh Candra Kirana Ds. Tugurejo Kec. Ngasem<br>website: rsudslg.kedirikab.go.id</p>
                        </td>
                        <td class="header-right">
                            <span class="cert-number">NO: {{ $lisensi->nomor }}</span>
                        </td>
                    </tr>
                </table>

                <h1 class="main-title">SERTIFIKAT <span>KOMPETENSI</span></h1>
                <div class="subtitle">Diberikan sebagai pengakuan kompetensi kepada:</div>

                <div class="candidate-box">
                    {{-- Prioritas Nama: dari Profile -> User --}}
                    <div class="candidate-name">
                        {{ strtoupper($profile->nama_lengkap ?? $user->name) }}
                    </div>

                    {{-- Prioritas ID: NIRP -> NIP -> Kosong --}}
                    <div class="candidate-id">
                        @if(!empty($profile->nirp))
                            NIRP. {{ $profile->nirp }}
                        @elseif(!empty($profile->nip))
                            NIP. {{ $profile->nip }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="desc-box">
                    Telah dinyatakan <strong>LULUS</strong> Uji Kompetensi Perawat Klinik.<br>
                    Kompetensi: <span class="highlight">{{ strtoupper($lisensi->nama) }}</span><br>
                    Area Klinik / Bidang: <span class="highlight">{{ strtoupper($lisensi->bidang) }}</span>

                    <div class="event-info">
                        Diselenggarakan: {{ \Carbon\Carbon::parse($lisensi->tgl_mulai)->isoFormat('D MMMM Y') }}
                        s/d {{ \Carbon\Carbon::parse($lisensi->tgl_diselenggarakan)->isoFormat('D MMMM Y') }}<br>
                        Berlaku hingga: <strong>{{ \Carbon\Carbon::parse($lisensi->tgl_expired)->isoFormat('D MMMM Y') }}</strong>
                    </div>
                </div>

                <table class="footer-table">
                    <tr>
                        <td class="footer-spacer"></td>
                        <td class="footer-sig">
                            <div class="sig-place-date">
                                Kediri, {{ \Carbon\Carbon::parse($lisensi->tgl_terbit)->isoFormat('D MMMM Y') }}
                            </div>
                            <div class="sig-title">DIREKTUR UOBK RSUD SLG</div>

                            <div style="height: 50px;">
                                </div>

                            <div class="sig-name">dr. TONY WIDYANTO, Sp.OG (K)</div>
                            <div class="sig-nip">NIP. 19750714 200212 1 006</div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
