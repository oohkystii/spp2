<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biaya;

class KepalaSekolahLaporanFormController extends Controller
{
    public function create(Request $request)
    {
        return view('kepala_sekolah.laporanform_index', [
            'biayaList' => Biaya::whereNull('parent_id')->pluck('nama', 'id'),
        ]);
    }
}
