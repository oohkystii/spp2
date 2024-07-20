<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class SettingTokenController extends Controller
{
    public function index()
    {
        $token = Token::first()->token ?? '';
        return view('operator.settingtoken_form', [
            'token' => $token,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $token = Token::first();
        if ($token) {
            $token->update($request->all());
        } else {
            Token::create($request->all());
        }

        return redirect()->route('settingtoken.index')->with('success', 'Token berhasil disimpan');
    }
}
