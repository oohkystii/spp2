<?php

namespace App\Http\Controllers;

use App\Exports\RekapPembayaranExport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KepalaSekolahLaporanRekapPembayaranController extends Controller
{
    public function index(Request $request) 
    {
        $siswa = Siswa::orderBy('nama', 'asc');
        if ($request->filled('kelas')) {
            $siswa->where('kelas', $request->kelas);
        }
        $siswa = $siswa->get();
        $dataRekap = [];

        // Loop through each student
        if (!$siswa->isEmpty()) {
            foreach ($siswa as $itemSiswa) {
                $dataTagihan = [];
                $tahun = $request->tahun;
                // Loop through each month from July to June
                foreach ($this->getBulanSPP() as $bulan) {
                    if ($bulan == 1) {
                        $tahun = $tahun + 1;
                    }
                    $tagihan = $itemSiswa->tagihan->filter(function ($value) use ($bulan, $tahun) {
                        return $value->tanggal_tagihan->year == $tahun && $value->tanggal_tagihan->month == $bulan;
                    })->first();
                    $dataTagihan[] = [
                        'bulan' => $this->ubahNamaBulan($bulan),
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
        $data['header'] = $this->getBulanSPP();
        $data['dataRekap'] = $dataRekap;
        $data['title'] = "Rekap Pembayaran";
        $data['kelas'] = $request->kelas ?? null;
        return view('kepala_sekolah.laporanrekappembayaran_index', $data);
    }

    public function exportToExcel(Request $request) 
    {
        $export = new RekapPembayaranExport($request->kelas, $request->tahun);
        return Excel::download($export, 'rekap_pembayaran.xlsx');
    }

    private function getBulanSPP()
    {
        return [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]; // July to June
    }

    private function ubahNamaBulan($bulan)
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $bulanIndo[$bulan];
    }
}
