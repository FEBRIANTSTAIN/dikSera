<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Cek Dokumen Expired
        $schedule->command('sertifikat:check-expiry')
            ->dailyAt('08:00')            
            ->timezone('Asia/Jakarta');   

        // 2. Telegram Polling
        // Perintah ini akan dipanggil scheduler setiap menit.
        // Tapi `withoutOverlapping` akan mencegahnya jalan kalau instance sebelumnya masih hidup.
        $schedule->command('telegram:polling')
            ->everyMinute()
            ->withoutOverlapping()  // Cek Apakah bot sedang jalan? Kalau ya, skip.
            ->runInBackground()     // Jalan di background 
            ->appendOutputTo(storage_path('logs/telegram-bot.log')); // Simpan log output
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
