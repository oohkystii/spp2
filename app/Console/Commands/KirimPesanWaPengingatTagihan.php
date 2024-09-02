<?php

namespace App\Console\Commands;

use App\Models\Tagihan;
use App\Services\WaBlasService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KirimPesanWaPengingatTagihan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:watagihan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pesan WhatsApp pengingat tagihan';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve the template text from the settings
        $templateTeksBaru = Settings('template_pengingat_tagihan');
        $templateTeksAngsur = Settings('template_pengingat_tagihan_angsur');
        if (!$templateTeksBaru || !$templateTeksAngsur) {
            $this->error('Template pengingat tagihan tidak ditemukan.');
            return 1;
        }

        $this->info("Template teks (Baru): $templateTeksBaru");
        $this->info("Template teks (Angsur): $templateTeksAngsur");

        // Get all pending tagihan (invoices)
        $tagihan = Tagihan::with('siswa', 'tagihanDetails')
                        ->whereIn('status', ['baru', 'angsur'])
                        ->get();

        if ($tagihan->isEmpty()) {
            $this->info('Tidak ada tagihan untuk diproses.');
            return 0;
        }

        $ws = new WaBlasService();

        foreach ($tagihan as $item) {
            // Log the tagihan details for debugging
            Log::info('Processing Tagihan ID: ' . $item->id);
            Log::info('Tagihan Details Count: ' . $item->tagihanDetails->count());

            // Initialize variables
            $totalBiaya = 0;
            $namaBiaya = [];

            // Calculate total biaya and collect nama biaya
            foreach ($item->tagihanDetails as $detail) {
                $totalBiaya += $detail->jumlah_biaya;
                $namaBiaya[] = $detail->nama_biaya;
            }

            // Combine all nama_biaya into a single string
            $namaBiayaString = implode(', ', $namaBiaya);

            // Calculate total payment and remaining balance if status is 'angsur'
            if ($item->status === 'angsur') {
                $totalDibayar = $item->pembayaran ? $item->pembayaran->sum('jumlah_dibayar') : 0;
                $sisaPembayaran = $totalBiaya - $totalDibayar;
            } else {
                $totalDibayar = 0;
                $sisaPembayaran = 0;
            }

            // Log the calculated biaya
            Log::info('Total Biaya: ' . $totalBiaya);
            Log::info('Total Dibayar: ' . $totalDibayar);
            Log::info('Sisa Pembayaran: ' . $sisaPembayaran);

            // Prepare the replacement array
            $templateReplace = [
                '{nama_biaya}' => $namaBiayaString,
                '{bulan}' => $item->tanggal_tagihan->translatedFormat('F'),
                '{tahun}' => $item->tanggal_tagihan->translatedFormat('Y'),
                '{nama}' => $item->siswa->nama,
                '{jatuh-tempo}' => $item->tanggal_jatuh_tempo->translatedFormat('Y-m-d'),
                '{jumlah_biaya}' => number_format($totalBiaya, 0, ',', '.'),
                '{total_dibayar}' => number_format($totalDibayar, 0, ',', '.'),
                '{sisa_pembayaran}' => number_format($sisaPembayaran, 0, ',', '.'),
            ];

            // Choose the correct template based on the status
            $templateTeks = $item->status === 'baru' ? $templateTeksBaru : $templateTeksAngsur;

            // Replace placeholders in the template
            $pesan = str_replace(array_keys($templateReplace), array_values($templateReplace), $templateTeks);
            $this->info("Pesan yang dikirim: $pesan");

            if ($item->siswa->wali && $item->siswa->wali->nohp) {
                $success = $ws->sendSingleMessage($item->siswa->wali->nohp, $pesan);
                $this->info("Pesan dikirim ke: " . $item->siswa->wali->nohp);
            } else {
                $this->info('No HP wali tidak ditemukan untuk siswa: ' . $item->siswa->nama);
            }
        }

        return 0;
    }
}
