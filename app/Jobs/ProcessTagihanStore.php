<?php

namespace App\Jobs;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use App\Notifications\TagihanNotification;
use App\Services\WaBlasService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use Notification;

class ProcessTagihanStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->prepareStatus();
    }

    public function handle()
    {
        $requestData = $this->requestData;
        $requestData['status'] = 'baru';
        $tanggalTagihan = Carbon::parse($requestData['tanggal_tagihan']);
        $bulanTagihan = $tanggalTagihan->format('m');
        $tahunTagihan = $tanggalTagihan->format('Y');

        // tanggal jatuh tempo
        $tanggalJatuhTempo = Carbon::parse($requestData['tanggal_jatuh_tempo']);
        $tanggalJatuhTempoFormatted = $tanggalJatuhTempo->format('Y-m-d');

        $tanggalPemberitahuan = Carbon::parse($requestData['tanggal_pemberitahuan']);
        $tanggalPemberitahuanFormatted = $tanggalPemberitahuan->format('Y-m-d H:i:s');

        $siswa = Siswa::with('biaya', 'tagihan', 'tagihan.tagihanDetails')->currentStatus('aktif');

        if (isset($requestData['siswa_id']) && $requestData['siswa_id'] != null) {
            $siswa = $siswa->where('id', $requestData['siswa_id']);
        }

        $siswa = $siswa->get();
        $numbersHPWali = [];
        $dataSiswa = [];
        $this->setProgressMax($siswa->count());
        $i = 1;
        $jumlahBiaya = 0;

        foreach ($siswa as $itemSiswa) {
            $this->setProgressNow($i);
            $i++;
            $requestData['siswa_id'] = $itemSiswa->id;
            $requestData['biaya_id'] = $itemSiswa->biaya_id;

            $cekTagihan = $itemSiswa->tagihan->filter(function ($value) use ($bulanTagihan, $tahunTagihan) {
                return $value->tanggal_tagihan->year == $tahunTagihan && $value->tanggal_tagihan->month == $bulanTagihan;
            })->first();

            if ($cekTagihan === null) {
                $tagihan = Tagihan::create($requestData);

                if ($tagihan->siswa->wali != null) {
                    $numbersHPWali[] = $itemSiswa->wali->nohp;
                    $dataSiswa[] = $itemSiswa->nama;
                    Notification::send($tagihan->siswa->wali, new TagihanNotification($tagihan));
                }

                $biaya = $itemSiswa->biaya->children;
                foreach ($biaya as $itemBiaya) {
                    TagihanDetail::create([
                        'tagihan_id' => $tagihan->id,
                        'nama_biaya' => $itemBiaya->nama,
                        'jumlah_biaya' => $itemBiaya->jumlah,
                    ]);
                }
            }
        }
        try {
            $wa = new WaBlasService();
            // $wa->sendMultipleMessage($numbersHPWali, $dataSiswa, $tanggalJatuhTempoFormatted, $requestData['tanggal_tagihan'], $jumlahBiaya);
            $wa->sendSchedulesMessage($numbersHPWali, $dataSiswa, $tanggalPemberitahuanFormatted, $requestData['tanggal_tagihan'], $tagihan->id, $tanggalJatuhTempoFormatted, $itemBiaya->nama);
            $this->setOutput(['message' => 'Tagihan Bulan ' . ubahNamaBulan($bulanTagihan) . ' ' . $tahunTagihan . ' berhasil dibuat']);
        } catch (\Exception $e) {
            $this->setOutput(['message' => 'Tagihan Bulan ' . ubahNamaBulan($bulanTagihan) . ' ' . $tahunTagihan . ' gagal dikirim']);
        }
    }
}