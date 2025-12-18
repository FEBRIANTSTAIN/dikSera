<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kompetensi - {{ $pengajuan->user->name }}</title>
    <style>
        /* Mengatur Ukuran Kertas A4 Landscape */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            background-color: #fff;
            color: #333;
            -webkit-print-color-adjust: exact;
        }

        /* --- Container Utama dengan Border Ornamen --- */
        .border-pattern {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid #2563eb; /* Primary Blue */
            padding: 5px;
        }

        .inner-border {
            border: 5px double #b45309; /* Gold Color */
            height: 100%;
            position: relative;
            background: #fff;
            /* Watermark Pattern (Opsional/CSS Only) */
            background-image: radial-gradient(#2563eb 0.5px, transparent 0.5px), radial-gradient(#2563eb 0.5px, #fff 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            background-color: #fff;
        }

        /* Layer putih di atas pattern agar teks terbaca jelas */
        .content-layer {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.94);
            padding: 40px;
            text-align: center;
        }

        /* --- Tipografi --- */
        .header-system {
            font-family: Arial, sans-serif;
            font-size: 14px;
            letter-spacing: 2px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header-title {
            font-family: Arial, sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #1e3a8a; /* Dark Blue */
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 30px;
        }

        .cert-type {
            font-family: 'Times New Roman', serif;
            font-size: 48px;
            font-weight: normal;
            color: #b45309; /* Gold */
            margin: 0;
            font-style: italic;
            line-height: 1;
        }

        .presented-to {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #4b5563;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .recipient-name {
            font-size: 38px;
            font-weight: 700;
            color: #111827;
            border-bottom: 2px solid #e5e7eb;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 20px;
            min-width: 400px;
            text-transform: uppercase;
        }

        .description {
            font-size: 16px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 10px;
            max-width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .license-name {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0 25px 0;
        }

        /* --- Footer & Signatures --- */
        .footer-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .sign-col {
            width: 40%;
            vertical-align: bottom;
            text-align: center;
        }

        .stamp-col {
            width: 20%;
            vertical-align: bottom;
            text-align: center;
        }

        .sign-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 0 auto 10px auto;
            height: 60px; /* Space for signature image */
        }

        .sign-name {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .sign-title {
            font-size: 12px;
            color: #6b7280;
        }

        /* --- Badge / Seal --- */
        .seal-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #b45309;
            color: #b45309;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            margin: 0 auto;
            opacity: 0.8;
        }

        /* --- Meta Info --- */
        .meta-info {
            margin-top: 40px;
            font-size: 10px;
            color: #9ca3af;
            font-family: Arial, sans-serif;
            border-top: 1px solid #f3f4f6;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="border-pattern">
        <div class="inner-border">
            <div class="content-layer">

                <div class="header-system">Diksera Hospital System</div>
                <h1 class="header-title">Rumah Sakit Umum Diksera</h1>

                <h2 class="cert-type">Sertifikat Kelulusan</h2>
                <div class="presented-to">Dokumen ini diberikan kepada:</div>

                <div class="recipient-name">{{ $pengajuan->user->name }}</div>

                <div class="description">
                    Atas keberhasilannya menyelesaikan seluruh rangkaian evaluasi kompetensi keperawatan<br>
                    dan dinyatakan <strong>KOMPETEN</strong> untuk perpanjangan lisensi:
                </div>

                <div class="license-name">
                    {{ $pengajuan->lisensiLama->nama }} &mdash; {{ $pengajuan->lisensiLama->lembaga }}
                </div>

                <div class="description" style="font-size: 14px; color: #6b7280;">
                    Metode Evaluasi:
                    @if($pengajuan->metode == 'pg_only')
                        Ujian Tulis (Computer Based Test)
                    @else
                        Ujian Tulis & Wawancara Klinis
                    @endif
                </div>

                <table class="footer-table">
                    <tr>
                        <td class="sign-col">
                            <div class="sign-line">
                                </div>
                            <div class="sign-name">Dr. Budi Santoso, MARS</div>
                            <div class="sign-title">Direktur Utama</div>
                        </td>

                        <td class="stamp-col">
                            <div class="seal-box">
                                <div style="text-align:center; padding-top: 25px;">
                                    OFFICIAL<br>SEAL
                                </div>
                            </div>
                        </td>

                        <td class="sign-col">
                            <div class="sign-line">
                                </div>
                            <div class="sign-name">Ns. Siti Aminah, S.Kep</div>
                            <div class="sign-title">Ketua Komite Keperawatan</div>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px;">
                    <tr>
                        <td style="text-align: left; font-size: 10px; color: #999; font-family: Arial;">
                            ID Dokumen: <strong>{{ $pengajuan->id }}/DKS-CERT/{{ date('Y') }}</strong>
                        </td>
                        <td style="text-align: right; font-size: 10px; color: #999; font-family: Arial;">
                            Diterbitkan: {{ \Carbon\Carbon::parse($pengajuan->updated_at)->isoFormat('D MMMM Y') }}
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

</body>
</html>
