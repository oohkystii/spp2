@extends('layouts.app_sneat_blank')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="mt-3">
            <a href="{{ route('export.rekap_pembayaran', ['kelas' => $kelas, 'tahun' => request('tahun')]) }}" class="btn btn-success">
                Export to Excel
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('kepala_sekolah.laporan_header')
                <h5 class="mt-4">LAPORAN PEMBAYARAN</h5>
                <p>Laporan Berdasarkan: {{ $title }}</p>

                <div class="table-responsive mt-3">
                    <table class="{{ config('app.table_style') }}">
                        <thead class="{{ config('app.thead_style') }}">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Siswa</th>
                                @foreach ($header as $bulan)
                                    <th>{{ ubahNamaBulan($bulan) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataRekap as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['siswa']['nama'] }}</td>
                                @foreach ($item['dataTagihan'] as $itemTagihan)
                                    <td class="text-center">
                                        @if ($itemTagihan['tanggal_lunas'] != '-')
                                            {{ optional($itemTagihan['tanggal_lunas'])->format('d/m') }}
                                            <div>
                                                {{ optional($itemTagihan['tanggal_lunas'])->format('Y') }}
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
if (!function_exists('ubahNamaBulan')) {
    function ubahNamaBulan($bulan)
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $bulanIndo[$bulan];
    }
}
@endphp
