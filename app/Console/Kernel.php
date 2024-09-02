<?php

namespace App\Console;

use App\Models\Tagihan; // Import model Tagihan
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
        // Ambil tanggal jatuh tempo dan waktu dari tagihans dengan status 'baru' atau 'angsur'
        $dueDate = $this->getDueDate(); // Fungsi untuk ambil tanggal jatuh tempo
        $dueTime = $this->getDueTime(); // Fungsi untuk ambil waktu (jam_ke)

        if ($dueDate && $dueTime) {
            // Jadwalkan tugas hanya pada tanggal jatuh tempo
            $schedule->command('kirim:watagihan')
                ->when(function () use ($dueDate) {
                    return now()->toDateString() === $dueDate; // Hanya jalankan pada tanggal jatuh tempo
                })
                ->dailyAt($dueTime); // Jalankan pada jam ke
        }
    }

    /**
     * Fungsi untuk mengambil tanggal jatuh tempo dari tagihans dengan status 'baru' atau 'angsur'.
     *
     * @return string|null
     */
    private function getDueDate()
    {
        // Ambil tanggal jatuh tempo dari tagihans dengan status 'baru' atau 'angsur'
        return Tagihan::whereIn('status', ['baru', 'angsur'])
                    ->orderBy('tanggal_jatuh_tempo', 'asc')
                    ->first()->tanggal_jatuh_tempo->toDateString() ?? null;
    }

    /**
     * Fungsi untuk mengambil waktu dari tagihans dengan status 'baru' atau 'angsur'.
     *
     * @return string
     */
    private function getDueTime()
    {
        // Ambil jam_ke dari tagihans dengan status 'baru' atau 'angsur'
        return Tagihan::whereIn('status', ['baru', 'angsur'])
                    ->orderBy('tanggal_jatuh_tempo', 'asc')
                    ->first()->jam_ke ?? '11:30'; // Default 07:00 jika tidak ada
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
