@extends('layouts.app_sneat_wali')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <img src="{{ \Storage::url($model->foto ?? 'images/no-image.png') }}" width="150">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <td width="15%">Status Siswa</td>
                                    <td>: 
                                        <span class="badge {{ $model->status == 'aktif' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $model->status }}
                                        </span>
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>: {{ $model->nama }}</td>
                                </tr>
                                <tr>
                                    <td>NISN</td>
                                    <td>: {{ $model->nisn }}</td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td>: {{ $model->jurusan }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>: {{ $model->kelas }}</td>
                                </tr>
                                <tr>
                                    <td>Angkatan</td>
                                    <td>: {{ $model->angkatan }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>: {{ $model->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Last Updated</td>
                                    <td>: {{ $model->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Update By</td>
                                    <td>: {{ $model->user->name }}</td>
                                </tr>
                            </thead>
                        </table>
                        <a href="{{ route('kartuspp.index', [
                            'siswa_id' => $model->id,
                            'tahun' => date('Y'),
                        ]) }}" target="_blank">
                            <i class="fa fa-file-pdf"></i> Download Kartu SPP
                        </a>
                        <h6 class="mt-3">Biaya Tagihan SPP</h6>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item Tagihan</th>
                                            <th>Jumlah Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($model->biaya->children as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td class="text-end">{{ formatRupiah($item->jumlah) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2">TOTAL TAGIHAN</td>
                                        <td class="text-end fw-bold">{{ formatRupiah($model->biaya->total_tagihan) }}</td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
