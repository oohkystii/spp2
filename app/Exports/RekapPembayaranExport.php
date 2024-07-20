<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapPembayaranExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $kelas;

    public function __construct($kelas = null)
    {
        $this->kelas = $kelas;
    }

    public function collection()
    {
        $siswaQuery = Siswa::orderBy('nama', 'asc');

        if ($this->kelas) {
            $siswaQuery->where('kelas', $this->kelas);
        }

        $siswa = $siswaQuery->get();

        if ($siswa->isEmpty()) {
            return collect([]);
        }

        $dataRekap = [];
        $months = ['JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER', 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI'];

        foreach ($siswa as $index => $itemSiswa) {
            $dataRow = [
                'NO' => $index + 1,
                'NAMA SISWA' => $itemSiswa->nama,
            ];

            $tahun = date('Y');
            $currentMonth = (int) date('n');

            for ($i = 0; $i < count($months); $i++) {
                $bulan = $i + 7;

                if ($bulan > 12) {
                    $bulan -= 12;
                    $tahun++;
                }

                $tagihan = $itemSiswa->tagihan->filter(function ($value) use ($bulan, $tahun) {
                    return $value->tanggal_tagihan->year == $tahun && $value->tanggal_tagihan->month == $bulan;
                })->first();

                $dataRow[$months[$i]] = $tagihan ? ($tagihan->tanggal_lunas ? $tagihan->tanggal_lunas->format('d/m/Y') : '-') : '-';
            }

            $dataRekap[] = $dataRow;
        }

        return collect($dataRekap);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA SISWA',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVEMBER',
            'DESEMBER',
            'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
        ];
    }
}
