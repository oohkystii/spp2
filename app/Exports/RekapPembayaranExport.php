<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapPembayaranExport implements FromView
{
    protected $kelas;
    protected $tahun;

    public function __construct($kelas = null, $tahun = null)
    {
        $this->kelas = $kelas;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $siswa = Siswa::orderBy('nama', 'asc');
        if ($this->kelas) {
            $siswa->where('kelas', $this->kelas);
        }
        $siswa = $siswa->get();
        $dataRekap = [];

        if (!$siswa->isEmpty()) {
            foreach ($siswa as $itemSiswa) {
                $dataTagihan = [];
                $tahun = $this->tahun;
                foreach (bulanSPP() as $bulan) {
                    if ($bulan == 1) {
                        $tahun = $tahun + 1;
                    }
                    $tagihan = $itemSiswa->tagihan->filter(function ($value) use ($bulan, $tahun) {
                        return $value->tanggal_tagihan->year == $tahun && $value->tanggal_tagihan->month == $bulan;
                    })->first();
                    $dataTagihan[] = [
                        'bulan' => ubahNamaBulan($bulan),
                        'tahun' => $tahun,
                        'tanggal_lunas' => $tagihan->tanggal_lunas ?? '-',
                    ];
                }
                $dataRekap[] = [
                    'siswa' => $itemSiswa,
                    'dataTagihan' => $dataTagihan
                ];
            }
        }

        return view('exports.rekappembayaran', [
            'dataRekap' => $dataRekap,
            'header' => bulanSPP()
        ]);
    }
}
