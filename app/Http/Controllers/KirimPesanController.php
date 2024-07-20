<?php

namespace App\Http\Controllers;

use App\Channels\WhacenterChannel;
use App\Models\User;
use Illuminate\Http\Request;

class KirimPesanController extends Controller
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
        return view('operator.kirimpesan_form', [
            'waliList' => User::where('akses', 'wali')->get()->pluck('name_with_nohp', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'wali_id' => 'nullable',
            'pesan' => 'required',
            'channels' => 'required',
        ]);
        $channels = $request->channels;
        if (in_array('whatsapp', $channels)) {
            // array search and replace whatsapp
            $channels[array_search('whatsapp', $channels)] = WhacenterChannel::class;
        }

        $users = User::where('akses', 'wali');

        if ($request->has('wali_id')) {
            $users->where('id', $request->wali_id);
        }

        $users->get()->each(function ($user) use ($request, $channels){
            $user->notify(new \App\Notifications\KirimPesanMassalNotification($channels, $request->pesan));
        });

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
