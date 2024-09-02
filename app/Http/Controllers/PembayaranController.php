<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pembayaran as Model;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use App\Models\Tagihan;
use App\Notifications\PembayaranKonfirmasiNotification;
use App\Services\WaBlasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Model::latest();
        if ($request->filled('bulan')) {
            $models = $models->whereMonth('tanggal_bayar', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $models = $models->whereYear('tanggal_taanggal', $request->tahun);
        }
        if ($request->filled('status')) {
            if ($request->status == 'sudah-konfirmasi') {
                $models = $models->whereNotNull('tanggal_konfirmasi');
            }
            if ($request->status == 'belum-konfirmasi') {
                $models = $models->whereNull('tanggal_konfirmasi');
            }
        }
        if ($request->filled('q')) {
            $models = $models->whereHas('tagihan', function ($q) {
                $q->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . request('q') . '%');
                });
            });
        }

        $data['models'] = $models->orderBy('tanggal_konfirmasi', 'desc')->paginate(50);
        $data['title'] = 'Data Pembayaran';
        return view('operator.pembayaran_index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePembayaranRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePembayaranRequest $request)
    {
        $requestData = $request->validated();
        $tagihan = Tagihan::findOrFail($requestData['tagihan_id']);
        $requestData['tanggal_konfirmasi'] = now();
        $requestData['metode_pembayaran'] = 'manual';
        $requestData['wali_id'] = $tagihan->siswa->wali_id ?? 0;
        // simpan pembayaran
        $pembayaran = Pembayaran::create($requestData);
        // kirim notifikasi pembayaran
        $wali = $pembayaran->wali;
        if ($wali != null) {
            $wa = new WaBlasService();
            // EDIT PESAN
            $message = 'Pembayaran anda sebesar Rp. ' . number_format($pembayaran->jumlah_dibayar, 0, ',', '.') . ' telah kami terima.';
            if (!$tagihan->isPaid()) {
                $sisaTagihan = $tagihan->totalTagihan - $tagihan->totalPembayaran;
                $message .= $sisaTagihan > 0 ? ' Sisa tagihan ' .$sisaTagihan .'.' : '';
            }
            $wa->sendSingleMessage($wali->nohp, $message .' Terima Kasih.');
            $wali->notify(new PembayaranKonfirmasiNotification($pembayaran));
        }
        flash('Pembayaran berhasil disimpan');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pembayaran $pembayaran)
    {
        auth()->user()->unreadNotifications->where('id', request('id'))->first()?->markAsRead();
        return view('operator.pembayaran_show', [
            'title' => 'Detail Pembayaran',
            'model' => $pembayaran,
            'route' => ['pembayaran.update', $pembayaran->id]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePembayaranRequest  $request
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $wali = $pembayaran->wali;
        $wali->notify(new PembayaranKonfirmasiNotification($pembayaran));
        $pembayaran->tanggal_konfirmasi = now();
        $pembayaran->user_id = auth()->user()->id;
        $pembayaran->save();
        // $pembayaran->tagihan->updateStatus();

        flash('Data pembayaran berhasil disimpan');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        flash('Data pembayaran berhasil dihapus');
        return back();
    }
}
