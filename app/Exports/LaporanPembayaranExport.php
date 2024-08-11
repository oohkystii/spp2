<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected static $rowNumber = 1;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $pembayaran = Pembayaran::query();

        if ($this->request->filled('bulan')) {
            $pembayaran->whereMonth('tanggal_bayar', $this->request->bulan);
        }
        if ($this->request->filled('tahun')) {
            $pembayaran->whereYear('tanggal_bayar', $this->request->tahun);
        }               
        if ($this->request->filled('angkatan')) {
            $pembayaran->whereHas('tagihan.siswa', function ($query) {
                $query->where('angkatan', $this->request->angkatan);
            });
        }  
        if ($this->request->filled('biaya_id')) {
            $pembayaran->whereHas('tagihan', function ($query) {
                $query->where('biaya_id', $this->request->biaya_id);
            });
        }        
        if ($this->request->filled('kelas')) {
            $pembayaran->whereHas('tagihan.siswa', function ($query) {
                $query->where('kelas', $this->request->kelas);
            });
        }        

        return $pembayaran->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama',
            'Angkatan',
            'Tanggal Bayar',
            'Metode Pembayaran',
            'Tanggal Konfirmasi',
            'Jumlah Bayar'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            self::$rowNumber++,
            $pembayaran->tagihan->siswa->nisn,
            $pembayaran->tagihan->siswa->nama,
            $pembayaran->tagihan->siswa->angkatan,
            $pembayaran->tanggal_bayar->format('d-m-Y'),
            $pembayaran->metode_pembayaran,
            optional($pembayaran->tanggal_konfirmasi)->format('d-m-Y'),
            'Rp. ' . number_format($pembayaran->jumlah_dibayar, 0, ',', '.')
        ];
    }
}
