<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\WaliBank;
use Auth;
use Illuminate\Http\Request;
use App\Notifications\PembayaranNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;


class WaliMuridPembayaranController extends Controller
{

    public function index() 
    {
        $pembayaran = Pembayaran::where('wali_id', auth()->user()->id)
            ->latest()
            ->orderBy('tanggal_konfirmasi', 'desc')
            ->paginate(50);
        $data['models'] = $pembayaran;
        return view('wali.pembayaran_index', $data);
    }

    public function show(Pembayaran $pembayaran)
    {
        auth()->user()->unreadNotifications->where('id', request('id'))->first()?->markAsRead();
        return view('wali.pembayaran_show', [
            'model' => $pembayaran,
        ]);        
    }

    public function create(Request $request)
    {
        // Jika user sudah pernah melakukan pembayaran dan menyimpan nomor rekening, tampilan di select
        $data['tagihan'] = Tagihan::waliSiswa()->findOrFail($request->tagihan_id);
        $data['model'] = new Pembayaran();
        $data['method'] = 'POST';
        $data['route'] = 'wali.pembayaran.store';
        $data['listBank'] = Bank::pluck('nama_bank', 'id');

        $data['url'] = route('wali.pembayaran.create', [
            'tagihan_id' => $request->tagihan_id,
        ]);

        return view('wali.pembayaran_form', $data);
    }

    
    public function store(Request $request)
    {
        $jumlahDibayar = str_replace('.', '', $request->jumlah_dibayar);
        $request->validate([
            'tanggal_bayar' => 'required',
            'jumlah_dibayar' => 'required',
            'bukti_bayar' => 'required|image|mimes:png,jpg,jpeg,svg|max:5048',
        ]);

        $buktiBayar = $request->file('bukti_bayar')->store('public');
        $dataPembayaran = [
            'tagihan_id' => $request->tagihan_id,
            'wali_id' => auth()->user()->id,
            'tanggal_bayar' => $request->tanggal_bayar . ' ' . date('H:i:s'),
            'jumlah_dibayar' => $jumlahDibayar,
            'bukti_bayar' => $buktiBayar,
            'metode_pembayaran' => 'transfer',
            'user_id' => 0,
        ];

        // validasi pembayaran harus lunas
        $tagihan = Tagihan::findOrFail($request->tagihan_id);
        if ($jumlahDibayar >= $tagihan->total_tagihan) {
            DB::beginTransaction();;
            try {
                // $pembayaran =  Pembayaran::create($dataPembayaran);
                $pembayaran = new Pembayaran();
                $pembayaran->fill($dataPembayaran);
                $pembayaran->saveQuietly();
                
                $userOperator = User::where('akses', 'operator')->get();
                Notification::send($userOperator, new PembayaranNotification($pembayaran));
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                flash()->addError('Gagal menyimpan data pembayaran, ' . $th->getMessage());
                return back();
            }
        } else {
            flash()->addError('Jumlah pembayaran tidak boleh kurang dari total tagihan');
            return back();
        }
        
        flash('Pembayaran berhasil disimpan dan akan segera di konfirmasi oleh operator');
        return redirect()->route('wali.pembayaran.show', $pembayaran->id);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->tanggal_konfirmasi != null) {
            flash()->addError('Data pembayaran ini sudah dikonfirmasi, tidak bisa dihapus');
            return back();
        }
        \Storage::delete($pembayaran->bukti_bayar);
        $pembayaran->delete();
        flash('Data pembayaran berhasil dihapus');
        return redirect()->route('wali.pembayaran.index');
    }

}