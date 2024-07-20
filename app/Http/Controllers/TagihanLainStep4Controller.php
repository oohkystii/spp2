<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTagihanLainStore;
use App\Models\Biaya;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Session;

class TagihanLainStep4Controller extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $biaya = Biaya::find(session('biaya_id'));
        if (session('tagihan_untuk') == 'semua') {
            $siswaId = Siswa::all()->pluck('id');
        } else if (session('tagihan_untuk') == 'pilihan') {
            $siswaId = session('data_siswa')->pluck('id');
        } else {
            Session::flash('error', 'Terjadi kesalahan, tidak ada data siswa yang akan ditagih');
            return back();
        }

        $tanggalTagihan = $request->tanggal_tagihan;
        $tanggalJatuhTempo = $request->tanggal_jatuh_tempo;
        $requestData['biaya_id'] = $biaya->id;
        $requestData['siswa_id'] = $siswaId;
        $requestData['tanggal_tagihan'] = $tanggalTagihan;
        $requestData['tanggal_jatuh_tempo'] = $tanggalJatuhTempo;
        $process = new ProcessTagihanLainStore($requestData);
        $this->dispatch($process);
        Session::flash('success', 'Tagihan berhasil dibuat');
        return redirect()->route('jobstatus.index');
    }
}
