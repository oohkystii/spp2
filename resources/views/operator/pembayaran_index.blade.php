@extends('layouts.app_sneat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">DATA PEMBAYARAN</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['route' => 'pembayaran.index', 'method' => 'GET']) !!}
                            <div class="row gx-2">
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => 'Pencarian Data Siswa']) !!}
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::select('kelas', getNamakelas(), null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Pilih Kelas',
                                    ]) !!}
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::select('status', 
                                        [
                                            '' => 'Pilih Status',
                                            'sudah-konfirmasi' => 'Sudah Dikonfirmasi',
                                            'belum-konfirmasi' => 'Belum Dikonfirmasi',
                                        ],
                                        request('status'), 
                                        ['class' => 'form-control'],
                                    ) !!}
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::selectMonth('bulan', request('bulan'), ['class' => 'form-control', 'placeholder' => 'Pilih Bulan']) !!}
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::selectRange('tahun', 2023, date('Y') + 1, request('tahun'), ['class' => 'form-control', 'placeholder' => 'Pilih Tahun']) !!}
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button class="btn btn-primary btn-block" type="submit">Tampil</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>                        
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="{{ config('app.table_style') }}">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status Konfirmasi</th>
                                    <th>Tanggal Konfirmasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($models as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tagihan->siswa->nama }}</td>
                                        <td>{{ $item->metode_pembayaran }}</td>
                                        <td>{{ $item->status_konfirmasi }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status_style }}">
                                                @if($item->tanggal_konfirmasi == null)
                                                    Belum Dikonfirmasi
                                                @else
                                                    {{ $item->tanggal_konfirmasi->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pembayaran.show', $item->id) }}" class="btn btn-info btn-sm mx-2">
                                                <i class="fa fa-info"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data Tidak Ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {!! $models->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
