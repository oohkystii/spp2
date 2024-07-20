<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Tagihan;
use Auth;
use Illuminate\Http\Request;
use Pdf;

class KartuSppController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::with('tagihan')->whereHas('tagihan', function ($q) {
            $q->where('jenis', 'spp');
        })->where('id', $request->siswa_id);

        if (Auth::user()->akses == 'wali') {
            $siswa = Siswa::where('wali_id', Auth::id());
        }
        $siswa = $siswa->firstOrFail();

        $tahun = $request->tahun;
        $arrayData = [];
        // perulangan berdasarkan bulan tagihan
        foreach (bulanSPP() as $bulan) {
            // jika bulan 1 maka tahun ditambah 1 karena tagihan dari juli sampai juni
            if ($bulan == 1) {
                $tahun = $tahun + 1;
            }
            // mencari tagihan berdasarkan siswa, tahun, bulan
            $tagihan = $siswa->tagihan->filter(function ($value) use ($bulan, $tahun) {
                return $value->tanggal_tagihan->year == $tahun && $value->tanggal_tagihan->month == $bulan;
            })->first();
            $tanggalBayar = '';
            // jika tagihan tidak kosong dan status tidak baru, berarti sudah bayar, maka ambil tanggal bayar
            if ($tagihan != null && $tagihan->status != 'baru') {
                $tanggalBayar = $tagihan->pembayaran->first()->tanggal_bayar->format('d/m/y');
            }
            // masukan data ke array
            $arrayData[] = [
                'bulan' => ubahNamaBulan($bulan),
                'tahun' => $tahun,
                'total_tagihan' => $tagihan->total_tagihan ?? 0,
                'status_tagihan' => ($tagihan == null) ? false : true,
                'status_pembayaran' => ($tagihan == null) ? 'Belum Bayar' : $tagihan->status,
                'tanggal_bayar' => $tanggalBayar,
            ];
        }
        if (request('output') == 'pdf') {
            $pdf = Pdf::loadView('kartuspp_index', [
                'kartuSpp' => collect($arrayData),
                'siswa' => $siswa
            ]);
            $namaFile = "Kartu SPP " . $siswa->nama . ' tahun ' . $request->tahun . ' . pdf ';
            return $pdf->download($namaFile);
        }
        return view('kartuspp_index', [
            'kartuSpp' => collect($arrayData),
            'siswa' => $siswa
        ]);
    }
}
