<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPembayaranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $pembayaranData = Pembayaran::all();

        // Memanipulasi data pembayaran sebelum diekspor
        $formattedData = [];
        $no = 1; // Mulai nomor dari 1

        foreach ($pembayaranData as $pembayaran) {
            $formattedData[] = [
                'No' => $no++, // Increment nomor dengan setiap iterasi
                'NISN' => $pembayaran->tagihan->siswa->nisn,
                'Nama' => $pembayaran->tagihan->siswa->nama,
                'Angkatan' => $pembayaran->tagihan->siswa->angkatan,
                'Tanggal Bayar' => $pembayaran->tanggal_bayar->format('Y-m-d H:i:s'),
                'Metode Pembayaran' => $pembayaran->metode_pembayaran,
                'Status Konfirmasi' => $pembayaran->status_konfirmasi,
                'Tanggal Konfirmasi' => optional($pembayaran->tanggal_konfirmasi)->format('Y-m-d H:i:s'),
                'Jumlah Bayar' => $pembayaran->jumlah_dibayar,
            ];
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
            'Tanggal Bayar',
            'Metode Pembayaran',
            'Status Konfirmasi',
            'Tanggal Konfirmasi',
            'Jumlah Bayar',
        ];
    }
}