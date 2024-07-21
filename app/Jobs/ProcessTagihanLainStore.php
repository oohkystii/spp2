<?php

namespace App\Jobs;

use App\Models\Biaya;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use App\Notifications\TagihanLainNotification;
use App\Services\WaBlasService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use Notification;

class ProcessTagihanLainStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $requestData;
    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requestData = $this->requestData;
        $requestData['status'] = 'baru';
        $requestData['jenis'] = 'lain-lain';
        $tanggalTagihan = Carbon::parse($requestData['tanggal_tagihan']);
        $bulanTagihan = $tanggalTagihan->format('m');
        $tahunTagihan = $tanggalTagihan->format('Y');

        $siswa = Siswa::with('biaya', 'tagihan', 'tagihan.tagihanDetails')->currentStatus('aktif');
        if (isset($requestData['siswa_id']) && $requestData['siswa_id'] != null) {
            $siswa = $siswa->whereIn('id', $requestData['siswa_id']);
        }
        $siswa = $siswa->get();
        $biaya = Biaya::find($requestData['biaya_id']);
        $biayaChildren = Biaya::where('parent_id', $requestData['biaya_id'])->get();
        $this->setProgressMax($siswa->count());
        $i = 1;
        $numbersHPWali = [];
        $dataSiswa = [];
        foreach ($siswa as $itemSiswa) {
            $this->setProgressNow($i);
            $i++;
            $requestData['siswa_id'] = $itemSiswa->id;
            $tagihan = Tagihan::create($requestData);
            if ($tagihan->siswa->wali != null) {
                $dataSiswa[] = $tagihan->nama;
                $numbersHPWali[] = $tagihan->siswa->wali->nohp;
                Notification::send($tagihan->siswa->wali, new TagihanLainNotification($tagihan));
            }
            foreach ($biayaChildren as $itemBiaya) {
                TagihanDetail::create([
                    'tagihan_id' => $tagihan->id,
                    'nama_biaya' => $itemBiaya->nama,
                    'jumlah_biaya' => $itemBiaya->jumlah,
                ]);
                $jumlahBiaya = $itemBiaya->jumlah;
            }
        }
        if (count($numbersHPWali) > 0) {
            $wa = new WaBlasService();
            $resultMultipleMessage = $wa->sendMultipleMessage($numbersHPWali, $dataSiswa, $requestData['tanggal_tagihan'], $requestData['tanggal_jatuh_tempo'], $jumlahBiaya);
            $result =  $wa->sendSchedulesMessage($numbersHPWali, $dataSiswa, $requestData['tanggal_tagihan'], $requestData['tanggal_tagihan'], $tagihan->id, $requestData['tanggal_jatuh_tempo']);
        }
        $this->setOutput(['message' => 'Tagihan biaya lain ' . $biaya->nama . ' berhasil dibuat']);
    }
}