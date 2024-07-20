<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\WhacenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Setting; // Tambahkan use statement untuk model Setting
use App\Services\WaBlasService;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function create()
    {
        $message = Message::first()->message ?? '';
        return view('operator.setting_form', ['message' => $message]);
    }
    public function store(Request $request)
    {
        $message = Message::first();
        if ($message) {
            $message->update([
                'message' => $request->template_pengingat_tagihan
            ]);
        } else {
            Message::create([
                'message' => $request->template_pengingat_tagihan
            ]);
        }
        // if ($request->has('tes_wa')) {
        //     $ws = new WhacenterService();
        //     $ws->line("Testing koneksi WA")->to($request->tes_wa)->send();
        //     flash('Pesan sudah dikirim');
        //     return back();
        // }

        $dataSettings = $request->except('_token');
        // Simpan tanda tangan
        // if ($request->input('output') && $request->input('img_signature_base64')) {
        //     // Decode signature
        //     $decodedSignature = explode(',', $request->input('img_signature_base64'));
        //     $decodedSignature = base64_decode(@$decodedSignature[1]);

        //     // Save File
        //     $fileName = 'signature_' . md5(time() . uniqid()) . ".png";
        //     Storage::disk('public')->put('signature/' . $fileName, $decodedSignature);

        //     // Delete data ttd lama jika ada
        //     if(settings()->get('pj_ttd_image')) {
        //         if(Storage::disk('public')->exists(settings()->get('pj_ttd_image'))) {
        //             Storage::disk('public')->delete(settings()->get('pj_ttd_image'));
        //         }
        //     }

        //     // Set data ke settings
        //     settings()->set('pj_ttd_o', $request->input('output'));
        //     settings()->set('pj_ttd_base64', $request->input('img_signature_base64'));
        //     settings()->set('pj_ttd_image', 'signature/' . $fileName);
        // }
        // if ($request->hasFile('app_logo')) {
        //     $request->validate([
        //         'app_logo' => 'required|mimes:png,jpg,jpeg|max:5000'
        //     ]);
        //     $dataSettings['app_logo'] = $request->file('app_logo')->store('public');
        // }
        settings()->set($dataSettings);
        Session::flash('success', 'Data sudah disimpan');
        return redirect()->back();
    }
}

    // public function store(Request $request)
    // {
    //     if ($request->has('tes_wa')) {
    //         $ws = new WhacenterService();
    //         $ws->line("Testing koneksi WA")->to($request->tes_wa)->send();
    //         flash('Pesan sudah dikirim')->success();
    //         return back();
    //     }

    //     $dataSettings = $request->except('_token');

    //     // Simpan tanda tangan
    //     if ($request->input('output') && $request->input('img_signature_base64')) {
    //         // Decode signature
    //         $decodedSignature = explode(',', $request->input('img_signature_base64'));
    //         $decodedSignature = base64_decode(@$decodedSignature[1]);
            
    //         // Save File
    //         $fileName = 'signature_' . md5(time() . uniqid()) . ".png";
    //         Storage::disk('public')->put('signature/' . $fileName, $decodedSignature);

    //         // Delete data ttd lama jika ada
    //         if(settings()->get('pj_ttd_image')) {
    //             if(Storage::disk('public')->exists(settings()->get('pj_ttd_image'))) {
    //                 Storage::disk('public')->delete(settings()->get('pj_ttd_image'));
    //             }
    //         }

    //         // Set data ke settings
    //         settings()->set('pj_ttd_o', $request->input('output'));
    //         settings()->set('pj_ttd_base64', $request->input('img_signature_base64'));
    //         settings()->set('pj_ttd_image', 'signature/' . $fileName);
    //     }

    //     // Simpan logo
    //     if ($request->hasFile('app_logo')) {
    //         $request->validate([
    //             'app_logo' => 'required|mimes:png,jpg,jpeg|max:5000'
    //         ]);
    //         $logoPath = $request->file('app_logo')->store('public');
            
    //         // Simpan logo ke dalam grup tertentu
    //         $dataSettings['val'] = [
    //             'logo' => $logoPath
    //         ];

    //         $dataSettings['group'] = 'group_logo'; // Ganti dengan grup yang sesuai
    //     }

    //     // Cek apakah tanda tangan ada, jika tidak, hapus tanda tangan yang ada
    //     if ($request->has('clear_signature')) {
    //         // Hapus tanda tangan dari penyimpanan (tetap sama)
    //         $dataSettings['val'] = null; // Ganti dengan grup yang sesuai
    //     }

    //     // Simpan data pengaturan dalam satu baris
    //     $settings = Setting::findOrNew(1); // Mengambil atau membuat baris dengan ID 1
    //     $settings->fill($dataSettings);
    //     $settings->save();

    //     Session::flash('success', 'Data sudah disimpan');
    //     return redirect()->back();
    // }
