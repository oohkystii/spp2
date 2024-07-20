<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Services\WaBlasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WaliMuridTagihanController extends Controller
{
    public function index()
    {
        $tagihan = Tagihan::waliSiswa()->latest();
        if (request()->filled('q')) {
            $tagihan = $tagihan->search(request('q'));
        }
        $data['tagihan'] = $tagihan->get();
        return view('wali.tagihan_index', $data);
    }

    public function show($id)
    {
        $tagihan = Tagihan::waliSiswa()->findOrFail($id);
        if ($tagihan->status == 'lunas') {
            $pembayaranId = $tagihan->pembayaran->last()->id;
            return redirect()->route('wali.pembayaran.show', $pembayaranId);
        }
        $statusPembayaran = '';
        if (request('check')) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(env('MIDTRANS_SERVER_KEY') . ':')
            ])->get('https://api.sandbox.midtrans.com/v2/' . $tagihan->id . '/status');
            $responseJson = $response->json();
            $statusPembayaran = $responseJson['transaction_status'];
            if ($statusPembayaran == 'settlement') {
                //update status pembayaran
                $requestData = [
                    'jumlah_dibayar' => $responseJson['gross_amount'],
                    'tagihan_id' => $tagihan->id,
                    'metode_pembayaran' => 'transper',
                    'wali_id' => $tagihan->siswa->wali_id ?? 0
                ];
                // simpan pembayaran
                $pembayaran = Pembayaran::firstOrCreate($requestData, [
                    'tanggal_bayar' => now(),
                    'tanggal_konfirmasi' => now(),
                ]);
            }

            $wa = new WaBlasService();
            // EDIT PESAN
            $wa->sendSingleMessage($tagihan->siswa->wali->nohp, 'Pembayaran tagihan ' . $tagihan->siswa->nama . ' sebesar ' . $tagihan->total_tagihan . ' telah lunas melalui ' . $responseJson['payment_type'] . ' pada ' . $responseJson['transaction_time'] . '. Terima kasih.');
        }
        $data['statusPembayaran'] = $statusPembayaran;
        $data['tagihan'] = $tagihan;
        $data['siswa'] = $tagihan->siswa;
        return view('wali.tagihan_show', $data);
    }
}
