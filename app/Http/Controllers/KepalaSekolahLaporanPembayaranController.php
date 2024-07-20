<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPembayaranExport;
use App\Models\Biaya;
use App\Models\Pembayaran;
use Excel;
use Illuminate\Http\Request;

class KepalaSekolahLaporanPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pembayaran = Pembayaran::query();
        $title = "LAPORAN PEMBAYARAN";
        $subtitle = "";
        if ($request->filled('bulan')) {
            $pembayaran = $pembayaran->whereMonth('tanggal_bayar', $request->bulan);
            $subtitle = $subtitle .  " Bulan " . ubahNamaBulan($request->bulan);
        }
        if ($request->filled('tahun')) {
            $pembayaran = $pembayaran->whereYear('tanggal_bayar', $request->tahun);
            $subtitle = $subtitle . " " . $request->tahun;
        }               
        if ($request->filled('angkatan')) {
            $pembayaran = $pembayaran->whereHas('tagihan', function ($q) use ($request) {
                $q->whereHas('siswa', function ($q) use ($request) {
                    $q->where('angkatan', $request->angkatan);
                });
            });
            $subtitle = $subtitle . " Angkatan " . $request->angkatan;
        }  
        if ($request->filled('biaya_id')) {
            $pembayaran = $pembayaran->whereHas('tagihan', function ($q) use ($request) {
                $q->where('biaya_id', $request->biaya_id);
            });
            $biaya = Biaya::findOrFail($request->biaya_id);
            $title = $title . " " . $biaya->nama;
        }        
        if ($request->filled('kelas')) {
            $pembayaran = $pembayaran->whereHas('tagihan.siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
            $title = $title . " Kelas " . $request->kelas;
        }        

        $pembayaran = $pembayaran->get();
        return view('kepala_sekolah.laporanpembayaran_index', compact('pembayaran', 'title', 'subtitle'));
    }

    public function exportToExcel(Request $request)
    {
        return Excel::download(new LaporanPembayaranExport($request), 'laporan_pembayaran.xlsx');
    }
}
