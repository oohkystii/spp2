@extends('layouts.app_sneat_blank')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="mt-3">
                <a href="{{ route('export.excel', [
                    'bulan' => request('bulan'), 
                    'tahun' => request('tahun'), 'status' => request('status'), 
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
                    <h5 class="mt-4">{{ strtoupper($titleHeader) }}</h5>
                    Laporan Berdasarkan : {{ $subtitle }}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th width="1%">No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Angkatan</th>
                                    <th>Tanggal Tagihan</th>
                                    <th>Status</th>
                                    <th>Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->siswa->nisn }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->siswa->angkatan }}</td>
                                        <td>
                                            {{ $item->tanggal_tagihan->translatedFormat(config('app.format_tanggal')) }}
                                        </td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ formatRupiah($item->tagihanDetails->sum('jumlah_biaya')) }}</td>                                       
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data Tidak Ada</td>
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
