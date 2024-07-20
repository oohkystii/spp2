<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Charts\PembayaranStatusChart;
use App\Charts\TagihanStatusChart;

class BerandaOperatorController extends Controller
{
    public function index(TagihanStatusChart $tagihanStatusChart, PembayaranStatusChart $pembayaranStatusChart)
    {
        $tahun = date('Y');
        $bulan = date('m');
        
        // Total jumlah siswa
        $data['totalSiswa'] = Siswa::count();
        
        // Informasi pembayaran
        $pembayaran = Pembayaran::whereYear('tanggal_bayar', $tahun)
            ->whereMonth('tanggal_bayar', $bulan)
            ->get();
        
        $data['totalPembayaran'] = $pembayaran->sum('jumlah_dibayar');
        $data['totalSiswaSudahBayar'] = $pembayaran->count();

        // Informasi tagihan
        $tagihan = Tagihan::with('siswa')
            ->whereYear('tanggal_tagihan', $tahun)
            ->whereMonth('tanggal_tagihan', $bulan)
            ->get();
        
        $tagihanBelumBayar = $tagihan->where('status', '<>', 'lunas');
        $tagihanSudahBayar = $tagihan->where('status', 'lunas');

        $data['totalTagihan'] = $tagihan->count();
        $data['tagihanBelumBayar'] = $tagihanBelumBayar;
        $data['tagihanSudahBayar'] = $tagihanSudahBayar;

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        $data['bulanTeks'] = ubahNamaBulan($bulan); // Pastikan fungsi ubahNamaBulan tersedia.
        $data['dataPembayaranBelumKonfirmasi'] = Pembayaran::whereNull('tanggal_konfirmasi')->get();
        
        // Chart tagihan berdasarkan status
        $labelTagihanStatusChart = ['lunas', 'angsur', 'baru'];
        
        $dataTagihanStatusChart = [
            $tagihan->where('status', 'lunas')->count(),
            $tagihan->where('status', 'angsur')->count(),
            $tagihan->where('status', 'baru')->count(),
        ];

        $data['tagihanStatusChart'] = $tagihanStatusChart->build($labelTagihanStatusChart, $dataTagihanStatusChart);
        
        // Ambil data tagihan untuk bulan dan tahun yang sama
        $tagihan = Tagihan::whereYear('tanggal_tagihan', $tahun)
            ->whereMonth('tanggal_tagihan', $bulan)
            ->get();

        // Hitung jumlah tagihan untuk setiap jenis tagihan
        $jenisTagihanWithCounts = $tagihan->groupBy('jenis')->map->count();

        // Persiapkan label jenis tagihan
        $labelJenisTagihanChart = $jenisTagihanWithCounts->keys()->toArray();
        $dataJenisTagihanChart = $jenisTagihanWithCounts->values()->toArray();

        // Jika ada jenis tagihan yang tidak memiliki data, tambahkan jumlah tagihan 0 untuk jenis tersebut
        $jenisTagihanDefault = [''];
        foreach ($jenisTagihanDefault as $jenis) {
            if (!in_array($jenis, $labelJenisTagihanChart)) {
                $labelJenisTagihanChart[] = $jenis;
                $dataJenisTagihanChart[] = 0;
            }
        }

        // Membangun grafik dengan label yang telah ditambahkan
        $data['jenisTagihanChart'] = $tagihanStatusChart->build($labelJenisTagihanChart, $dataJenisTagihanChart);

        return view('operator.beranda_index', $data);
    }
}
