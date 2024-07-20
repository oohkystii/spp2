<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class PembayaranMidtransController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->filled('tagihan_id')) {
            return $this->createOrder($request->tagihan_id);
        }
    }

    private function createOrder($id) {
        $biayaAdministrasi = 4000;
        $tagihan = Tagihan::waliSiswa()->findOrFail($id);
        $totalTagihan = $tagihan->totalTagihan + $biayaAdministrasi;
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        $params = array(
            'transaction_details' => array(
                'order_id' => $id,
                'gross_amount' => $totalTagihan,
            )
        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json([
            'snapToken' => $snapToken,
        ], 200);
    }
}
