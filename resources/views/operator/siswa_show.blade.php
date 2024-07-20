@extends('layouts.app_sneat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <td rowspan="10" class="align-top" width="100">
                                        <img src="{{ \Storage::url($model->foto) }}" width="120">
                                    </td>
                                    <td width="15%">Status Siswa</td>
                                    <td>:
                                        @if($model->status == 'aktif')
                                            <span class="badge bg-primary">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>Nama</td>
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
                        <h6 class="mt-3">TAGIHAN SPP</h6>
                        <div class="col-md-5">
                            <table class="table table-bordered table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Item Tagihan</th>
                                        <th>Jumlah Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($model->biaya && $model->biaya->children)
                                        @foreach ($model->biaya->children as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td class="text-end">{{ formatRupiah($item->jumlah) }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">Data Biaya Tidak Tersedia</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if ($model->biaya && $model->biaya->children)
                                        <tr>
                                            <td colspan="2" class="text-end">TOTAL TAGIHAN</td>
                                            <td class="text-end fw-bold">{{ formatRupiah($model->biaya->children->sum('jumlah')) }}</td>
                                        </tr>
                                    @endif
                                </tfoot>                                
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('status.update', [
                                    'model' => 'siswa',
                                    'id' => $model->id,
                                    'status' => $model->status == 'aktif' ? 'non-aktif' : 'aktif',
                                ]) }}" 
                                class="btn btn-primary btn-sm mt-3"
                                onclick="return confirm('Anda yakin?')">
                                {{ $model->status == 'aktif' ? 'Non-Aktifkan Siswa Ini' : 'Aktifkan Siswa Ini' }}
                                </a>
                            </div>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
