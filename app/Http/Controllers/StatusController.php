<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Session;

class StatusController extends Controller
{
    public function update(Request $request)
    {
        if ($request->model == 'siswa') {
            $model = Siswa::findOrFail($request->id);
            $model->setStatus($request->status);
            $model->save();
            Session::flash('success', 'Status berhasil diubah');
            return back();
        }
    }
}
