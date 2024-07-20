<?php

namespace App\Exports;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanTagihanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $tagihanData = Tagihan::query();
        if ($this->request->filled('bulan')) {
            $tagihanData = $tagihanData->whereMonth('tanggal_tagihan', $this->request->bulan);
        }
        if ($this->request->filled('tahun')) {
            $tagihanData = $tagihanData->whereYear('tanggal_tagihan', $this->request->tahun);
        }
        if ($this->request->filled('status')) {
            $tagihanData = $tagihanData->where('status', $this->request->status);
        }
        if ($this->request->filled('angkatan')) {
            $tagihanData = $tagihanData->whereHas('siswa', function ($q) {
                $q->where('angkatan', $this->request->angkatan);
            });
        }
        if ($this->request->filled('jurusan')) {
            $tagihanData = $tagihanData->whereHas('siswa', function ($q) {
                $q->where('jurusan', $this->request->jurusan);
            });
        }
        if ($this->request->filled('kelas')) {
            $tagihanData = $tagihanData->whereHas('siswa', function ($q) {
                $q->where('kelas', $this->request->kelas);
            });
        }

        $tagihanData = $tagihanData->get();

        // Memanipulasi data sebelum diekspor
        $formattedData = [];
        $nomorUrut = 1;

        foreach ($tagihanData as $tagihan) {
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
