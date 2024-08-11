<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanTagihanExport implements FromCollection, WithHeadings
{
    protected $tagihanData;

    public function __construct($tagihanData)
    {
        $this->tagihanData = $tagihanData;
    }

    public function collection()
    {
        // You can directly use $this->tagihanData here
        $formattedData = [];
        $nomorUrut = 1;

        foreach ($this->tagihanData as $tagihan) {
            $formattedData[] = [
                'No' => $nomorUrut,
                'NISN' => $tagihan->siswa->nisn,
                'Nama' => $tagihan->siswa->nama,
                'Angkatan' => $tagihan->siswa->angkatan,
                'Tanggal Tagihan' => $tagihan->tanggal_tagihan->format('Y-m-d H:i:s'),
                'Status' => $tagihan->status,
                'Total Tagihan' => $tagihan->tagihanDetails->sum('jumlah_biaya'),
            ];

            $nomorUrut++;
        }

        return collect($formattedData);
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama',
            'Angkatan',
            'Tanggal Tagihan',
            'Status',
            'Total Tagihan',
        ];
    }
}
