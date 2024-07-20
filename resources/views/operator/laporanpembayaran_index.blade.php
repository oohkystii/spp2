@extends('layouts.app_sneat_blank')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="mt-3">
                <a href="{{ route('export.pembayaran', [
                    'bulan' => request('bulan'), 
                    'tahun' => request('tahun'), 
                    'status' => request('status'), 
                    'angkatan' => request('angkatan'), 
                    'kelas' => request('kelas'), 
                    'biaya_id' => request('biaya_id')]) }}" 
                    class="btn btn-success">Export to Excel
                </a>
            </div>      
            <div class="card">
                <h5 class="card-header"></h5>
                <div class="card-body">
                    @include('operator.laporan_header')
                    <h5 class="mt-4">{{ strtoupper($title) }}</h5>
                    <p>{{ $subtitle }}</p>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th width="1%">No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Angkatan</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Tanggal Konfirmasi</th>
                                    <th>Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayaran as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tagihan->siswa->nisn }}</td>
                                        <td>{{ $item->tagihan->siswa->nama }}</td>
                                        <td>{{ $item->tagihan->siswa->angkatan }}</td>
                                        <td>
                                            {{ $item->tanggal_bayar->translatedFormat(config('app.format_tanggal')) }}
                                        </td>
                                        <td>{{ $item->metode_pembayaran }}</td>
                                        <td>
                                            {{ optional($item->tanggal_konfirmasi)->translatedFormat(config('app.format_tanggal')) }}
                                        </td>
                                        <td>{{ formatRupiah($item->jumlah_dibayar) }}</td>                                       
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Data Tidak Ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
