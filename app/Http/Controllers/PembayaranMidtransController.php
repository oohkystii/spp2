<?php 
namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class PembayaranMidtransController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->filled('tagihan_id')) {
            // Menentukan apakah ini adalah pembayaran penuh atau angsur
            $tagihan = Tagihan::waliSiswa()->findOrFail($request->tagihan_id);

            if ($request->filled('metode_pembayaran') && $request->metode_pembayaran === 'full') {
                // Pembayaran penuh
                return $this->createOrder($request->tagihan_id, 'full');
            } else {
                // Pembayaran angsuran
                if ($tagihan->angsuran_ke === 1) {
                    // Pembayaran kedua
                    return $this->createOrder($request->tagihan_id, 2);
                } else {
                    // Pembayaran pertama
                    return $this->createOrder($request->tagihan_id, 1);
                }
            }
        }
    }

    private function createOrder($id, $metodePembayaran) 
    {
        $biayaAdministrasi = 4000;
        $tagihan = Tagihan::waliSiswa()->findOrFail($id);
        $totalTagihan = $tagihan->totalTagihan + $biayaAdministrasi;

        // Ambil jumlah yang sudah dibayar dari tabel pembayaran
        $jumlahDibayar = \App\Models\Pembayaran::where('tagihan_id', $id)->sum('jumlah_dibayar');

        if ($metodePembayaran === 'full') {
            // Pembayaran penuh, total tagihan langsung dibayarkan
            $jumlahBayar = $totalTagihan;
        } else {
            // Pembayaran angsuran
            $jumlahBayar = $totalTagihan / 2;

            // Jika ini pembayaran kedua, jumlah yang harus dibayar adalah sisa tagihan
            if ($metodePembayaran === 2) {
                $jumlahBayar = $totalTagihan - $jumlahDibayar;
            }
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        
        $params = array(
            'transaction_details' => array(
                'order_id' => $id . '-' . $metodePembayaran, // Order ID yang unik
                'gross_amount' => $jumlahBayar,
            )
        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        return response()->json([
            'snapToken' => $snapToken,
            'metodePembayaran' => $metodePembayaran,
        ], 200);
    }

}
