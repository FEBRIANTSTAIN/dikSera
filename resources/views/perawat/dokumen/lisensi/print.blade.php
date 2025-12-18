<!DOCTYPE html>
<html>
<head>
    <title>Lisensi {{ $lisensi->nama }}</title>
    <style>
        body { font-family: sans-serif; text-align: center; border: 5px solid #2563eb; padding: 50px; }
        .header { margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #2563eb; text-transform: uppercase; }
        .subtitle { font-size: 14px; color: #555; }
        .content { margin: 40px 0; text-align: left; line-height: 1.6; }
        .field { font-weight: bold; width: 150px; display: inline-block; }
        .footer { margin-top: 50px; font-size: 12px; color: #888; }
        .signature { margin-top: 50px; text-align: right; margin-right: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SURAT KETERANGAN LISENSI</div>
        <div class="subtitle">{{ $lisensi->lembaga }}</div>
    </div>

    <hr>

    <div class="content">
        <p>Dengan ini menerangkan bahwa:</p>

        <div style="margin-left: 50px;">
            <p><span class="field">Nama</span> : {{ $user->name }}</p>
            <p><span class="field">Email</span> : {{ $user->email }}</p>
            <p><span class="field">Jenis Lisensi</span> : {{ $lisensi->nama }}</p>
            <p><span class="field">Nomor</span> : <strong>{{ $lisensi->nomor }}</strong></p>
            <p><span class="field">Masa Berlaku</span> : {{ \Carbon\Carbon::parse($lisensi->tgl_terbit)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($lisensi->tgl_expired)->format('d-m-Y') }}</p>
        </div>

        <p>Dokumen ini adalah bukti sah kepemilikan lisensi yang terdaftar pada sistem DIKSERA.</p>
    </div>

    <div class="signature">
        <p>Jakarta, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>Admin Verifikator</strong></p>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem DIKSERA pada {{ now() }}
    </div>
</body>
</html>
