@extends('layouts.app_sneat_blank')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mt-3">
        <div class="card-header">{{ __('Panduan Pembayaran Melalui Internet Banking') }}</div>

        <div class="card-body">
          <h1 class="text-center mb-4">Selamat datang di Panduan Pembayaran Melalui Internet Banking</h1>

          <p>Untuk melakukan pembayaran melalui Internet Banking, ikuti langkah-langkah berikut:</p>

          <ol>
            <li>Masuk ke akun Internet Banking Anda.</li>
            <li>Pilih opsi "Transfer" atau "Pembayaran" dalam menu.</li>
            <li>Pilih rekening sumber dan rekening tujuan.</li>
            <li>Masukkan jumlah pembayaran yang ingin Anda transfer.</li>
            <li>Verifikasi informasi pembayaran Anda.</li>
            <li>Klik "Kirim" atau "Selesaikan" untuk menyelesaikan transaksi.</li>
          </ol>

          <p>Jika Anda mengalami masalah atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi layanan pelanggan kami di nomor berikut: <strong>089-775-716-54</strong>.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
