<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliMuridSiswaController extends Controller
{
    public function index()
    {
        $data['models'] = Auth::user()->siswa;
        return view('wali.siswa_index', $data);
    }

    public function show($id)
    {
        $data['title'] = "Detail Data Siswa";
        $data['model'] = \App\Models\Siswa::with('biaya', 'biaya.children')
            ->where('id', $id)
            ->where('wali_id', Auth::user()->id)
            ->firstOrFail();
        return view('wali.siswa_show', $data);
    }
}
