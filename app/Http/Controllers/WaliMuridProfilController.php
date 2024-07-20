<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class WaliMuridProfilController extends Controller
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
        $data = [
            'model' => \App\Models\User::findOrFail(Auth::user()->id),
            'method' => 'POST',
            'route' => 'wali.profil.store',
            'button' => 'UBAH',
            'title' => 'FORM UBAH PROFIL'
        ];
        return view('wali.profil_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::user()->id;
        $requestData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'nohp' => 'required|unique:users,nohp,' . $id,
            'password' => 'nullable'
        ]);
        $model = \App\Models\User::findOrFail($id);
        if ($requestData['password'] == ""){
            unset($requestData['password']);
        } else {
            $requestData['password'] = bcrypt($requestData['password']);
        }
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public');
            $requestData['foto'] = $fotoPath;
        } 
        $model->fill($requestData);
        $model->save();
        flash('Data Berhasil Diubah');
        return back();
    }
}
