<?php

namespace App\Console\Commands;

use App\Models\Tagihan;
use App\Services\WhacenterService;
use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $templateTeks = Settings('template_pengingat_tagihan');

        $templateReplace = [
            '{bulan}' => 'Januari',
            '{tahun}' => '2024',
            '{nama}' => 'Kysti Qoriah'
        ];

        $templateTeks = str_replace(array_keys($templateReplace), array_values($templateReplace), $templateTeks);
        echo $templateTeks;

        $tagihan = Tagihan::with('siswa')->where('status', 'baru')->get();
        $templateTeks = Settings('template_pengingat_tagihan');
        if($templateReplace != '') {
            foreach ($tagihan as $item) {
                $templateReplace = [
                    '{bulan}' => $item->tanggal_tagihan->translatedFormat('F'),
                    '{tahun}' => $item->tanggal_tagihan->translatedFormat('Y'),
                    '{nama}' => $item->siswa->nama
                ];
                $pesan = str_replace(array_keys($templateReplace), array_values($templateReplace), $templateTeks);
                if ($item->siswa->wali!= null && $item->siswa->wali->nohp != null) {
                    $ws = new WhacenterService();
                    $ws->Line($pesan)->to($item->siswa->wali->nohp)->send();
                }
            }
        }
    }
}
