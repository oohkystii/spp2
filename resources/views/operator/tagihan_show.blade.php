@extends('layouts.app_sneat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('operator.tagihan_datasiswa')
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-5">
            <div class="card mt-2">
                <h5 class="card-header">DATA TAGIHAN {{ strtoupper($periode) }}</h5>
                <div class="card-body">
                    @include('operator.tagihan_table_tagihan')
                </div>
            </div>
            <div class="card mt-2">
                <h5 class="card-header">DATA PEMBAYARAN</h5>
                <div class="card-body">
                    @include('operator.tagihan_table_pembayaran')
                </div>
                <h5 class="card-header">FORM PEMBAYARAN</h5>
                <div class="card-body">
                    @include('operator.tagihan_form_pembayaran')
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <h5 class="card-header">KARTU SPP</h5>
                <div class="card-body">
                    @include('operator.tagihan_kartuspp')
                </div>
            </div>
        </div>
    </div>
@endsection