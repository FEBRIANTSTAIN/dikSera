<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PerawatSip;
use App\Models\PerawatStr;
use App\Models\PerawatLisensi;
use App\Models\PerawatDataTambahan;
use App\Services\TelegramService;
use Carbon\Carbon;

class CheckSertifikatExpiry extends Command
{
    protected $signature = 'sertifikat:check-expiry';
    protected $description = 'Cek masa berlaku sertifikat perawat dan kirim notifikasi';

    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    public function handle()
    {
        $this->info('Memulai pengecekan masa berlaku sertifikat...');

        $reminderDays = [90, 60, 30, 14, 7, 3, 1, 0];
        $notificationSent = 0;

        // Cek SIP
        $this->info('Mengecek SIP...');
        $sips = PerawatSip::with('user.profile')->get();
        foreach ($sips as $sip) {
            $daysLeft = Carbon::now()->diffInDays(Carbon::parse($sip->tgl_expired), false);

            if (in_array($daysLeft, $reminderDays) || $daysLeft < 0) {
                // Kirim ke admin (chat_id global)
                $this->telegramService->notifySertifikatExpiring($sip->user, $sip, 'SIP', $daysLeft);

                // Kirim ke user jika punya telegram_chat_id
                if ($sip->user->telegram_chat_id) {
                    $this->telegramService->notifySertifikatExpiringToUser(
                        $sip->user->telegram_chat_id,
                        $sip,
                        'SIP',
                        $daysLeft
                    );
                }

                $notificationSent++;
                $this->line("✓ Notifikasi SIP: {$sip->user->name} (Sisa {$daysLeft} hari)");
            }
        }

        // Cek STR
        $this->info('Mengecek STR...');
        $strs = PerawatStr::with('user.profile')->get();
        foreach ($strs as $str) {
            $daysLeft = Carbon::now()->diffInDays(Carbon::parse($str->tgl_expired), false);

            if (in_array($daysLeft, $reminderDays) || $daysLeft < 0) {
                $this->telegramService->notifySertifikatExpiring($str->user, $str, 'STR', $daysLeft);

                if ($str->user->telegram_chat_id) {
                    $this->telegramService->notifySertifikatExpiringToUser(
                        $str->user->telegram_chat_id,
                        $str,
                        'STR',
                        $daysLeft
                    );
                }

                $notificationSent++;
                $this->line("✓ Notifikasi STR: {$str->user->name} (Sisa {$daysLeft} hari)");
            }
        }

        // Cek Lisensi
        $this->info('Mengecek Lisensi...');
        $lisensis = PerawatLisensi::with('user.profile')->get();
        foreach ($lisensis as $lisensi) {
            $daysLeft = Carbon::now()->diffInDays(Carbon::parse($lisensi->tgl_expired), false);

            if (in_array($daysLeft, $reminderDays) || $daysLeft < 0) {
                $this->telegramService->notifySertifikatExpiring($lisensi->user, $lisensi, 'LISENSI', $daysLeft);

                if ($lisensi->user->telegram_chat_id) {
                    $this->telegramService->notifySertifikatExpiringToUser(
                        $lisensi->user->telegram_chat_id,
                        $lisensi,
                        'LISENSI',
                        $daysLeft
                    );
                }

                $notificationSent++;
                $this->line("✓ Notifikasi Lisensi: {$lisensi->user->name} (Sisa {$daysLeft} hari)");
            }
        }

        // Cek Data Tambahan
        $this->info('Mengecek Data Tambahan...');
        $dataTambahans = PerawatDataTambahan::with('user.profile')->get();
        foreach ($dataTambahans as $data) {
            if ($data->tgl_expired) {
                $daysLeft = Carbon::now()->diffInDays(Carbon::parse($data->tgl_expired), false);

                if (in_array($daysLeft, $reminderDays) || $daysLeft < 0) {
                    $this->telegramService->notifySertifikatExpiring(
                        $data->user,
                        $data,
                        strtoupper($data->jenis),
                        $daysLeft
                    );

                    if ($data->user->telegram_chat_id) {
                        $this->telegramService->notifySertifikatExpiringToUser(
                            $data->user->telegram_chat_id,
                            $data,
                            strtoupper($data->jenis),
                            $daysLeft
                        );
                    }

                    $notificationSent++;
                    $this->line("✓ Notifikasi {$data->jenis}: {$data->user->name} (Sisa {$daysLeft} hari)");
                }
            }
        }

        $this->info("✓ Selesai! Total {$notificationSent} notifikasi terkirim.");
        return 0;
    }
}
