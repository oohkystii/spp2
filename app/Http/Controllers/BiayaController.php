<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBiayaRequest;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateBiayaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\Request;
use \App\Models\Biaya as Model;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;



class BiayaController extends Controller
{
    private $viewIndex = 'biaya_index';
    private $viewCreate = 'biaya_form';
    private $viewEdit = 'biaya_form';
    private $viewShow = 'biaya_show';
    private $routePrefix = 'biaya';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->filled('q')){
            $models = Model::with('user')->whereNull('parent_id')->search($request->q)->paginate(settings()->get('app_pagination', '50'));
        } else {
            $models = Model::with('user')->whereNull('parent_id')->latest()->paginate(settings()->get('app_pagination', '50'));
        }
        return view('operator.' . $this->viewIndex, [
            'models' => $models,
            'routePrefix' => $this->routePrefix,
            'title' => 'Data Biaya',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $biaya = new Model();
        if ($request->filled('parent_id')) {
            $biaya = Model::with('children')->findOrFail($request->parent_id);
        }

        $data = [
            'parentData' => $biaya,
            'model' => new Model(),
            'method' => 'POST',
            'route' => $this->routePrefix . '.store',
            'button' => 'SIMPAN',
            'title' => 'FORM DATA BIAYA',
        ];

        return view('operator.' . $this->viewCreate, $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBiayaRequest $request)
    {
        Model::create($request->validated());
        flash('Data berhasil disimpan');
        return back();        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('operator.' . $this->viewShow, [
            'model' => Model::findOrFail($id),
            'title' => 'Detail Biaya'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'model' => Model::findOrFail($id),
            'method' => 'PUT',
            'route' => [$this->routePrefix . '.update', $id],
            'button' => 'UPDATE',
            'title' => 'FORM DATA BIAYA',
        ];
        return view('operator.' . $this->viewEdit, $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBiayaRequest $request, $id)
    {
        $model = Model::findOrFail($id);
        $model->fill($request->validated());
        $model->save();
        flash('Data berhasil diubah');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Model::findOrFail($id);
        // validasi ke relasi children
        if ($model->children->count() >= 1) {
            Session::flash('error', 'Data tidak bisa dihapus karena masih memiliki item biaya. Hapus item biaya terlebih dahulu');
            return back();
        }
        // validasai relasi ke table siswa
        if ($model->siswa->count() >=1) {
            Session::flash('error', 'Data gagal dihapus karena masih memiliki relasi ke data siswa');
            return back();
        }
        $model->delete();
        flash('Data berhasil dihapus');
        return back();
    }

    public function deleteItem($id)
    {
        $model = Model::findOrFail($id);
        if ($model->parent->siswa->count() >= 1) {
            Session::flash('error', 'Data gagal dihapus karena terkait data lain');
            return back();
        }
        $model->delete();
        flash('Data berhasil dihapus');
        return back();
    }
}
