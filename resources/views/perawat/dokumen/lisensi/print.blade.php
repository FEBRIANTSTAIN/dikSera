<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Lisensi - {{ $lisensi->nomor }}</title>
    <style>
        /* --- SETUP HALAMAN --- */
        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            font-family: 'Times New Roman', Times, serif; /* Font resmi surat */
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* --- BINGKAI HALAMAN (Border Dekoratif) --- */
        .page-border {
            position: fixed;
            top: 10mm; left: 10mm; right: 10mm; bottom: 10mm;
            border: 3px double #1565c0; /* Garis ganda biru tua */
            z-index: -1;
        }

        /* --- CONTAINER UTAMA --- */
        .container {
            padding: 25mm 25mm; /* Margin dalam dari bingkai */
        }

        /* --- WATERMARK --- */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            opacity: 0.08; /* Sangat transparan */
            z-index: -2;
        }

        /* --- KOP SURAT --- */
        .kop-table {
            width: 100%;
            border-bottom: 4px double #000; /* Garis Kop */
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .logo {
            width: 80px; height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-instansi {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
        }
        .kop-sub {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            font-weight: normal;
        }
        .kop-alamat {
            font-size: 9pt;
            font-style: italic;
        }

        /* --- JUDUL DOKUMEN --- */
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-main {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .nomor-surat {
            font-size: 11pt;
            margin-top: 2px;
        }

        /* --- ISI KONTEN --- */
        .paragraf {
            text-align: justify;
            margin-bottom: 15px;
        }

        /* --- TABEL DATA --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 20px; /* Indent sedikit */
            font-family: Arial, Helvetica, sans-serif; /* Pindah ke sans-serif biar jelas */
            font-size: 10pt;
        }
        .data-table td {
            padding: 5px 5px;
            vertical-align: top;
        }
        .label {
            width: 160px;
            font-weight: bold;
            color: #333;
        }
        .separator {
            width: 10px;
        }
        .value {
            color: #000;
        }
        .nomor-lisensi {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 11pt;
            letter-spacing: 1px;
            background: #eef2ff;
            padding: 2px 5px;
            border-radius: 3px;
            display: inline-block;
        }

        /* --- STATUS BADGE --- */
        .status-box {
            border: 1px solid #16a34a;
            background-color: #f0fdf4;
            color: #166534;
            padding: 5px 10px;
            font-weight: bold;
            display: inline-block;
            border-radius: 4px;
            font-size: 9pt;
        }

        /* --- TANDA TANGAN --- */
        .ttd-table {
            width: 100%;
            margin-top: 40px;
        }
        .ttd-col-kiri {
            width: 40%;
            text-align: center;
            vertical-align: bottom;
        }
        .ttd-col-kanan {
            width: 60%;
            text-align: right; /* Nama di kanan */
            padding-right: 20px;
        }
        .ttd-container {
            display: inline-block;
            text-align: center;
            width: 250px; /* Lebar area TTD */
        }
        .ttd-space {
            height: 70px;
        }
        .pejabat-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        /* QR Code Simulasi */
        .qr-placeholder {
            border: 2px solid #000;
            width: 70px; height: 70px;
            margin: 0 auto;
            display: flex; align-items: center; justify-content: center;
            font-size: 7pt; font-family: sans-serif; text-align: center;
        }

        .footer-note {
            position: fixed;
            bottom: 15mm; left: 25mm; right: 25mm;
            font-size: 8pt;
            color: #666;
            font-family: Arial, Helvetica, sans-serif;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="page-border"></div>

    <div class="container">
        <img src="https://rsudslg.kedirikab.go.id/asset_compro/img/logo/Logo.png" class="watermark" alt="Watermark">

        <table class="kop-table">
            <tr>
                <td width="15%" style="text-align: center; vertical-align: middle;">
                    <img src="https://rsudslg.kedirikab.go.id/asset_compro/img/logo/Logo.png" class="logo" alt="Logo">
                </td>
                <td width="85%" class="kop-text">
                    <div class="kop-instansi">PEMERINTAH KABUPATEN KEDIRI</div>
                    <div class="kop-instansi" style="font-size: 12pt;">DINAS KESEHATAN</div>
                    <div class="kop-instansi" style="font-size: 16pt; color: #1565c0;">UOBK RSUD SLG</div>
                    <div class="kop-sub">SISTEM INFORMASI KOMPETENSI PERAWAT (DIKSERA)</div>
                    <div class="kop-alamat">Jl. Galuh Candra Kirana Ds. Tugurejo Kec. Ngasem, Kediri - Jawa Timur</div>
                </td>
            </tr>
        </table>

        <div class="judul-surat">
            <div class="judul-main">SURAT KETERANGAN LISENSI</div>
            <div class="nomor-surat">Nomor Dokumen: {{ $lisensi->nomor }}</div>
        </div>

        <div class="paragraf">
            Yang bertanda tangan di bawah ini, Direktur UOBK RSUD Simpang Lima Gumul Kediri menerangkan dengan sesungguhnya bahwa:
        </div>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="separator">:</td>
                <td class="value">
                    <strong>
                        {{ strtoupper($profile->nama_lengkap ?? $user->name) }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td class="label">NIP / NIRP</td>
                <td class="separator">:</td>
                <td class="value">
                    @if(!empty($profile->nirp))
                        {{ $profile->nirp }} (NIRP)
                    @elseif(!empty($profile->nip))
                        {{ $profile->nip }} (NIP)
                    @else
                        {{ $user->nomor_induk ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="separator">:</td>
                <td class="value">{{ $profile->unit_kerja ?? 'Keperawatan Umum' }}</td>
            </tr>
            <tr>
                <td colspan="3" style="height: 10px;"></td> </tr>
            <tr>
                <td class="label">Jenis Lisensi</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ strtoupper($lisensi->nama) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Bidang Keahlian</td>
                <td class="separator">:</td>
                <td class="value">{{ $lisensi->bidang }}</td>
            </tr>
            <tr>
                <td class="label">Jenjang KFK</td>
                <td class="separator">:</td>
                <td class="value">{{ $lisensi->kfk }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Lisensi</td>
                <td class="separator">:</td>
                <td class="value"><span class="nomor-lisensi">{{ $lisensi->nomor }}</span></td>
            </tr>
            <tr>
                <td class="label">Masa Berlaku</td>
                <td class="separator">:</td>
                <td class="value">
                    {{ \Carbon\Carbon::parse($lisensi->tgl_terbit)->isoFormat('D MMMM Y') }}
                    s/d
                    <strong>{{ \Carbon\Carbon::parse($lisensi->tgl_expired)->isoFormat('D MMMM Y') }}</strong>
                </td>
            </tr>
            <tr>
                <td class="label">Status Verifikasi</td>
                <td class="separator">:</td>
                <td class="value">
                    <span class="status-box">✓ TERVERIFIKASI AKTIF</span>
                </td>
            </tr>
        </table>

        <div class="paragraf">
            Dokumen ini diterbitkan sebagai bukti sah kompetensi perawat yang bersangkutan sesuai dengan data yang tercatat pada database Kepegawaian dan Sistem DIKSERA RSUD SLG.
        </div>

        <div class="paragraf">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </div>

        <table class="ttd-table">
            <tr>
                <td class="ttd-col-kiri">
                    <div class="qr-placeholder">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ $lisensi->nomor }}" alt="QR" style="width: 70px;">
                    </div>
                    <div style="font-size: 8pt; margin-top: 5px;">Scan untuk validasi</div>
                </td>
                <td class="ttd-col-kanan">
                    <div class="ttd-container">
                        <div style="margin-bottom: 5px;">Kediri, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
                        <div style="font-weight: bold; margin-bottom: 5px;">DIREKTUR UOBK RSUD SLG</div>

                        <div class="ttd-space">
                            </div>

                        <div class="pejabat-nama">dr. TONY WIDYANTO, Sp.OG (K)</div>
                        <div>NIP. 19750714 200212 1 006</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <div class="footer-note">
        Dokumen ini telah ditandatangani secara elektronik. Validitas dokumen dapat dicek melalui sistem DIKSERA.<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
