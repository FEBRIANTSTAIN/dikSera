<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import Model User

class TelegramService
{
    protected $botToken;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * Helper internal untuk kirim request ke Telegram
     */
    private function executeSendMessage($chatId, $message)
    {
        if (empty($this->botToken) || empty($chatId)) {
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Telegram Error (ID: $chatId): " . $e->getMessage());
            return false;
        }
    }

    /**
     * 1. Notifikasi untuk ADMIN (Broadcast ke semua Admin)
     * Dipanggil oleh: notifySertifikatExpiring di Command
     */
    public function notifySertifikatExpiring($nurseUser, $sertifikat, $tipeSertifikat, $daysLeft)
    {
        // Cari semua admin yang sudah connect telegram
        $admins = User::where('role', 'admin')
            ->whereNotNull('telegram_chat_id')
            ->get();

        if ($admins->isEmpty()) {
            return false; // Tidak ada admin yang bisa dikirim
        }

        // Format Pesan Khusus Admin (Lebih Detail dengan Nama Perawat)
        $status = $this->getStatusText($daysLeft);
        $tglExp = date('d/m/Y', strtotime($sertifikat->tgl_expired ?? $sertifikat->tgl_berakhir ?? now()));

        $message = "<b>🚨 LAPORAN DOKUMEN PERAWAT</b>\n\n";
        $message .= "<b>Perawat:</b> {$nurseUser->name}\n";
        $message .= "<b>NIK:</b> " . ($nurseUser->profile->nik ?? '-') . "\n\n";

        $message .= "<b>Jenis:</b> {$tipeSertifikat}\n";
        $message .= "<b>Nomor:</b> {$sertifikat->nomor}\n";
        $message .= "<b>Expired:</b> {$tglExp}\n";
        $message .= "<b>Status:</b> {$status}\n\n";
        $message .= "<i>Mohon konfirmasi ke perawat terkait.</i>";

        // Loop kirim ke semua admin
        $successCount = 0;
        foreach ($admins as $admin) {
            if ($this->executeSendMessage($admin->telegram_chat_id, $message)) {
                $successCount++;
            }
        }

        return $successCount > 0;
    }

    /**
     * 2. Notifikasi untuk USER/PERAWAT (Personal)
     * Dipanggil oleh: notifySertifikatExpiringToUser di Command
     */
    public function notifySertifikatExpiringToUser($chatId, $sertifikat, $tipeSertifikat, $daysLeft)
    {
        // Format Pesan Personal
        $status = $this->getStatusText($daysLeft);
        $tglExp = date('d/m/Y', strtotime($sertifikat->tgl_expired ?? $sertifikat->tgl_berakhir ?? now()));

        $message = "<b>🔔 PENGINGAT MASA BERLAKU</b>\n\n";
        $message .= "Halo, dokumen <b>{$tipeSertifikat}</b> Anda membutuhkan perhatian.\n\n";
        $message .= "<b>Nomor:</b> {$sertifikat->nomor}\n";
        $message .= "<b>Expired:</b> {$tglExp}\n";
        $message .= "<b>Status:</b> {$status}\n\n";

        if ($daysLeft <= 0) {
            $message .= "⛔ <b>Dokumen sudah tidak aktif.</b> Segera urus perpanjangan.";
        } else {
            $message .= "📝 Segera lakukan perpanjangan sebelum tanggal tersebut.";
        }

        return $this->executeSendMessage($chatId, $message);
    }

    /**
     * Helper Status Text
     */
    private function getStatusText($daysLeft)
    {
        if ($daysLeft < 0) return "🔴 SUDAH KADALUARSA (" . abs($daysLeft) . " hari lalu)";
        if ($daysLeft == 0) return "🔴 HARI INI KADALUARSA";
        if ($daysLeft <= 3) return "🔴 KRITIS (Sisa {$daysLeft} hari)";
        if ($daysLeft <= 30) return "🟠 AKAN HABIS (Sisa {$daysLeft} hari)";
        return "⚠️ REMINDER (Sisa {$daysLeft} hari)";
    }

    /**
     * Fungsi Verifikasi 
     */
    public function sendMessage($message)
    {
        return false;
    }

    public function sendVerificationCode($chatId, $code)
    {
        $message = "🔐 <b>Kode Verifikasi Telegram</b>\n\n";
        $message .= "Kode Anda: <code>{$code}</code>\n\n";
        $message .= "Masukkan kode ini di aplikasi untuk menghubungkan akun.\n";
        $message .= "Kode berlaku 15 menit.";

        return $this->executeSendMessage($chatId, $message);
    }
}
