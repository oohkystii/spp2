<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Storage;

class SettingBendaharaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('operator.settingbendahara_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataSettings = $request->except('_token');
        // Simpan tanda tangan
        if ($request->input('output') && $request->input('img_signature_base64')) {
            // Decode signature
            $decodedSignature = explode(',', $request->input('img_signature_base64'));
            $decodedSignature = base64_decode(@$decodedSignature[1]);
            
            // Save File
            $fileName = 'signature_' . md5(time() . uniqid()) . ".png";
            Storage::disk('public')->put('signature/' . $fileName, $decodedSignature);

            // Delete data ttd lama jika ada
            if(settings()->get('pj_ttd_image')) {
                if(Storage::disk('public')->exists(settings()->get('pj_ttd_image'))) {
                    Storage::disk('public')->delete(settings()->get('pj_ttd_image'));
                }
            }

            // Set data ke settings
            settings()->set('pj_ttd_o', $request->input('output'));
            settings()->set('pj_ttd_base64', $request->input('img_signature_base64'));
            settings()->set('pj_ttd_image', 'signature/' . $fileName);
        }
        settings()->set($dataSettings);
        Session::flash('success', 'Data sudah disimpan');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
