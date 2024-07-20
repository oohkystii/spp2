<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Charts\SiswaKelasChart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class KepalaSekolahSiswaController extends Controller
{
    public function index(Request $request)
    {
        $routePrefix = 'siswa';
    
        $models = Siswa::with('wali', 'user')->latest();
        
        // Pencarian Data
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $models->where(function ($query) use ($searchTerm) {
                $query->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nisn', 'like', '%' . $searchTerm . '%');
            });
        }
        $title = 'Data Siswa';
    
        return view('kepala_sekolah.siswa_index', [
            'models' => $models->paginate(settings()->get('app_pagination', '50')),
            'routePrefix' => $routePrefix,
            'title' => $title,
        ]);
    }
}
