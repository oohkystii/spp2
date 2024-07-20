<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Session;

class TagihanLainStep2Controller extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->action == 'delete') {
            $siswaSession = session('data_siswa');
            $siswaData= $siswaSession->reject(function ($value) use ($request) {
                return $value->id == $request->id;
            });
            session(['data_siswa' => $siswaData]);
            Session::flash('success','Data sudah dihapus dari daftar pilihan');
            return redirect()->back();
        }

        if ($request->action == 'deleteall') {
            session()->forget('data_siswa');
            Session::flash('success','Data sudah dihapus dari daftar pilihan');
            return back();
        }

        $siswaIdArray = $request->siswa_id;
        $siswa = Siswa::whereIn('id', $siswaIdArray)->get();
        session(['data_siswa' => $siswa]);
        return back();
    }
}
