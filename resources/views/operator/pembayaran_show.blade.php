@extends('layouts.app_sneat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">DATA PEMBAYARAN</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="bg-secondary text-white fw-bold">INFORMASI SISWA</td>
                                </tr>
                                <tr>
                                    <td>Nama Siswa</td>
                                    <td>{{ $model->tagihan->siswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Wali</td>
                                    <td>{{ optional($model->wali)->name }}</td>
                                </tr>                                
                                <tr>
                                    <td colspan="2" class="bg-secondary text-white fw-bold">INFORMASI TAGIHAN</td>
                                </tr>
                                <tr>
                                    <td>Nomor Tagihan</td>
                                    <td>{{ $model->tagihan_id }}</td>
                                </tr>
                                <tr>
                                    <td>Invoice Tagihan</td>
                                    <td>
                                        <a href="{{ route('invoice.show', $model->tagihan_id) }}" target="_blank">
                                            <i class="fa fa-file-pdf"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Tagihan</td>
                                    <td>{{ formatRupiah($model->tagihan->tagihanDetails->sum('jumlah_biaya')) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bg-secondary text-white fw-bold">INFORMASI PEMBAYARAN</td>
                                </tr>
                                <tr>
                                    <td>Metode Pembayaran</td>
                                    <td>{{ $model->metode_pembayaran }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pembayaran</td>
                                    <td>{{ optional($model->tanggal_bayar)->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah Total Tagihan</td>
                                    <td>{{ formatRupiah($model->tagihan->tagihanDetails->sum('jumlah_biaya')) }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah Yang Dibayar</td>
                                    <td>{{ formatRupiah($model->jumlah_dibayar) }}</td>
                                </tr>
                                <tr>
                                    <td>Bukti Pembayaran</td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="popupCenter({url: '{{ \Storage::url($model->bukti_bayar) }}', title: 'Bukti Pembayaran', w:900, h: 700 }); ">
                                            Lihat Bukti Bayar
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Konfirmasi</td>
                                    <td>{{ $model->status_konfirmasi }}</td>
                                </tr>
                                <tr>
                                    <td>Status Pembayaran</td>
                                    <td>{{ $model->tagihan->getStatusTagihanWali() }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Konfirmasi</td>
                                    <td>{{ optional($model->tanggal_konfirmasi)->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @if ($model->tanggal_konfirmasi == null)
                        {!! Form::open([
                        'route' => $route,
                        'method' => 'PUT',
                        'onsubmit' => 'return confirm("Apakah anda yakin?")',
                        ]) !!}
                        {!! Form::hidden('pembayaran_id', $model->id, []) !!}
                        <div class="mt-3">
                            {!! Form::submit('Konfirmasi Pembayaran', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                        @else
                        <div class="alert alert-primary text-center mt-3">
                            <h3>TAGIHAN INI SUDAH LUNAS</h3>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
