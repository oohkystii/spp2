<?php

namespace App\Http\Controllers;

use App\Exports\LaporanTagihanExport;
use App\Models\Biaya;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KepalaSekolahLaporanTagihanController extends Controller
{
    public function index(Request $request)
    {
        // Assuming you're passing the request to filterTagihan
        $tagihan = $this->filterTagihan($request);
        $subtitle = $this->generateSubtitle($request);
        $titleHeader = "Laporan Tagihan";
        $title = "Laporan Tagihan"; // Define $title here

        return view('kepala_sekolah.laporantagihan_index', compact('tagihan', 'titleHeader', 'subtitle', 'title'));
    }


    public function exportToExcel(Request $request)
    {
        $tagihan = $this->filterTagihan($request);
        $export = new LaporanTagihanExport($tagihan);
        return Excel::download($export, 'laporan_tagihan.xlsx');
    }

    private function filterTagihan(Request $request)
    {
        $tagihan = Tagihan::query();

        if ($request->filled('bulan')) {
            $tagihan->whereMonth('tanggal_tagihan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $tagihan->whereYear('tanggal_tagihan', $request->tahun);
        }
        if ($request->filled('biaya_id')) {
            $tagihan->where('biaya_id', $request->biaya_id);
        }
        if ($request->filled('status')) {
            $tagihan->where('status', $request->status);
        }
        if($request->filled('angkatan')) {
            $tagihan->whereHas('siswa', function ($q) use ($request) {
                $q->where('angkatan', $request->angkatan);
            });
        }
        if($request->filled('jurusan')) {
            $tagihan = $tagihan->whereHas('siswa', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }
        if($request->filled('kelas')) {
            $tagihan = $tagihan->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        return $tagihan->get();
    }

    private function generateSubtitle(Request $request)
    {
        $subtitle = "Laporan Berdasarkan";

        if ($request->filled('bulan')) {
            $subtitle .= " Bulan " . ubahNamaBulan($request->bulan);
        }
        if ($request->filled('tahun')) {
            $subtitle .= " " . $request->tahun;
        }
        if ($request->filled('biaya_id')) {
            $biaya = Biaya::findOrFail($request->biaya_id);
            $subtitle .= " Jenis Tagihan " . $biaya->nama;
        }
        if ($request->filled('status')) {
            $subtitle .= " Status Tagihan " . $request->status;
        }
        if($request->filled('angkatan')) {
            $subtitle .= " Angkatan " . $request->angkatan;
        }
        if($request->filled('jurusan')) {
            $subtitle .= " Jurusan " . $request->jurusan;
        }
        if($request->filled('kelas')) {
            $subtitle .= " Kelas " . $request->kelas;
        }
        return $subtitle;
    }
}