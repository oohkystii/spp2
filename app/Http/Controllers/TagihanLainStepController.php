<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Session;

class TagihanLainStepController extends Controller
{
    public function create(Request $request)
    {
        if ($request->step == 1) {
            return $this->step1();
        }
        if ($request->step == 2) {
            return $this->step2();
        }
        if ($request->step == 3) {
            return $this->step3();
        }
        if ($request->step == 4) {
            return $this->step4();
        }
    }

    public function step1()
    {
        session()->forget('data_siswa');
        session()->forget('tagihan_untuk');
        $data['activeStep1'] = 'active';
        return view('operator.tagihanlain_step1', $data);
    }
    public function step2()
    {
        if (!request()->filled('tagihan_untuk')) {
            flash('error', 'Silahkan pilih tagihan untuk siapa');
            return redirect()->route('tagihanlainstep.create', ['step' => 1]);
        }
        
        session(['tagihan_untuk' => request('tagihan_untuk')]);

        if (request('tagihan_untuk') == 'semua') {
            session(['all_students_selected' => true]); // Set session variable to indicate all students are selected
            return redirect()->route('tagihanlainstep.create', ['step' => 3]);
        }

        session()->forget('all_students_selected'); // Clear the session variable if not "semua"

        $query = Siswa::query();
        if (request()->filled('cari')) {
            //cari data siswa berdasarkan query di url
            $query->when(request()->filled('nama'), function ($query) {
                $query->where('nama', 'like', '%' . request('nama') . '%');
            })->when(request()->filled('kelas'), function ($query) {
                $query->where('kelas', request('kelas'));
            })->when(request()->filled('angkatan'), function ($query) {
                $query->where('angkatan', request('angkatan'));
            });
        }
        $data['siswa'] = $query->get()->each(function ($q) {
            $q->checked = false;
            if (session('data_siswa') != null && session('data_siswa')->contains('id', $q->id)) {
                $q->checked = true;
            }
        });

        $data['activeStep2'] = 'active';
        return view('operator.tagihanlain_step2', $data);
    }

    public function step3()
    {
        if (session('tagihan_untuk') == '') {
            Session::flash('error', 'Silahkan pilih tagihan untuk siapa');
            return redirect()->route('tagihanlainstep.create', ['step' => 1]);
        }

        // Check if all students are selected
        if (session('tagihan_untuk') != 'semua' && (session('data_siswa') == null || session('data_siswa')->isEmpty())) {
            Session::flash('error', 'Silahkan pilih data siswa');
            return redirect()->route('tagihanlainstep.create', ['step' => 2]);
        }
        
        $data['activeStep3'] = 'active';
        $data['biayaList'] = Biaya::whereNull('parent_id')->get()->pluck('nama', 'id');
        return view('operator.tagihanlain_step3', $data);
    }

    public function step4()
    {
        if (session('tagihan_untuk') == '' && request('biaya_id') != '') {
            Session::flash('error', 'Silahkan pilih biaya yang akan ditagihkan');
            return redirect()->route('tagihanlainstep.create', ['step' => 3]);
        }
        session(['biaya_id' => request('biaya_id')]);
        $data['activeStep4'] = 'active';
        $data['biaya'] = Biaya::findOrFail(request('biaya_id'));
        if (session('tagihan_untuk') == 'semua') {
            $data['siswa'] = Siswa::all();
        } else {
            $data['siswa'] = Siswa::whereIn('id', session('data_siswa')->pluck('id'))->get();   
        }
        return view ('operator.tagihanlain_step4', $data);
    }
}
