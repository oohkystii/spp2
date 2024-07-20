@extends('layouts.app_sneat_wali')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">DATA TAGIHAN</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="{{ config('app.table_style') }}">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jenis Tagihan</th>
                                    <th>Bulan Tagihan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->siswa->kelas }}</td>
                                        <td>{{ $item->biaya->nama }}</td>
                                        <td>{{ $item->tanggal_tagihan->translatedFormat('F Y') }}</td>
                                        <td>
                                            @if ($item->pembayaran->count() >= 1)
                                                <a href="{{ route('wali.pembayaran.show', $item->pembayaran->first()->id) }}" class="btn btn-success btn-sm">
                                                    {{ $item->pembayaran->first()->tanggal_konfirmasi == null ? 'Belum dikonfirmasi' : 'Sudah dibayar' }}
                                                </a>
                                            @else
                                                {{ $item->getStatustagihanWali() }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 'baru' || $item->status == 'angsur')
                                                <a href="{{ route('wali.tagihan.show', $item->id) }}" class="btn btn-primary btn-sm">Lakukan Pembayaran</a>
                                            @else
                                                <span class="btn btn-success btn-sm">Pembayaran Sudah Lunas</span>
                                            @endif
                                        </td>
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
