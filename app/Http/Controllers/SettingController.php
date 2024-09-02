<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        $dataSettings = $request->except('_token');
        settings()->set($dataSettings);
        Session::flash('success', 'Data sudah disimpan');
        return redirect()->back();
    }
}