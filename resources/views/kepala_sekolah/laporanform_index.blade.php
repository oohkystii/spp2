@extends('layouts.app_sneat_kepala_sekolah')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Laporan</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Laporan Tagihan</h5>
                            @include('kepala_sekolah.laporanform_tagihan')
                        </div>                        
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Laporan Pembayaran</h5>
                            @include('kepala_sekolah.laporanform_pembayaran')
                        </div>                        
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Laporan Rekap Pembayaran SPP</h5>
                            @include('kepala_sekolah.laporanform_rekappembayaran')
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection