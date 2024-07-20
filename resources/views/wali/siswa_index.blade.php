@extends('layouts.app_sneat_wali')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">DATA SISWA</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="{{ config('app.table_style') }}">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Angkatan</th>
                                    <th>Kartu SPP</th>
                                    <th>Biaya Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($models as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div> {{ $item->nama }} </div>
                                            <div> {{ $item->nisn }} </div>
                                        </td>
                                        <td>{{ $item->kelas }}</td>
                                        <td>{{ $item->angkatan }}</td>
                                        <td>
                                            <a href="{{ route('kartuspp.index', [
                                                'siswa_id' => $item->id,
                                                'tahun' => date('Y'),
                                            ]) }}" target="_blank">
                                                <i class="fa fa-file-pdf"></i> Download
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('wali.siswa.show', $item->id) }}">
                                                @if ($item->biaya && $item->biaya->children)
                                                    {{ formatRupiah($item->biaya->children->sum('jumlah')) }}
                                                @else
                                                    <!-- Handle the case where 'biaya' or 'children' is null -->
                                                    0
                                                @endif
                                                <i class="fa fa-arrow-alt-circle-right"></i>
                                            </a>
                                        </td>                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" align="center">Data Tidak Ada</td>
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
