@extends('layouts.app_sneat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">{{ $title }}</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                        <button class="btn btn-secondary btn-sm" id="btn-div" data-bs-toggle="modal" data-bs-target="#importModal">Import Dari File Excel</button>
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                        <div class="input-group mt-3">
                            <input name="q" class="form-control" placeholder="Cari Nama Siswa"
                                   aria-label="cari nama" aria-describedby="button-addon2" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="table-responsive mb-3 mt-3">
                    <table class="{{ config('app.table_style') }}">
                        <thead class="{{ config('app.thead_style') }}">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Wali</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Angkatan</th>
                                <th>Biaya SPP</th>
                                <th width="26%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($models as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->wali->name ?? 'Belum Ada' }}</td>
                                    <td>
                                        <div><a href="{{ route('kartuspp.index', [
                                            'siswa_id' => $item->id,
                                            'tahun' => date('Y'),
                                        ]) }}" target="_blank"><i class="fa fa-id-card"></i></a> {{ $item->nama }}</div>
                                        <div>{{ $item->nisn }}</div>
                                    </td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->angkatan }}</td>
                                    <td>{{ formatRupiah($item->biaya?->first()->total_tagihan) }}</td>
                                    <td>
                                        {!! Form::open([
                                            'route' => [$routePrefix . '.destroy', $item->id],
                                            'method' => 'DELETE',
                                            'onsubmit' => 'return confirm("Yakin ingin menghapus data ini?")',
                                        ]) !!}
                                        <a href="{{ route($routePrefix .'.show', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-info"></i> Detail
                                        </a>
                                        <a href="{{ route($routePrefix .'.edit', $item->id) }}" class="btn btn-warning btn-sm mx-2">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data Tidak Ada</td>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'siswaimport.store', 'method' => 'POST', 'files' => true]) !!}
                <div class="mb-3">
                    <label for="inputGroupFile04" class="form-label">Upload File (Excel)</label>
                    <input type="file" name="template" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                </div>
                <button class="btn btn-primary btn-sm" type="submit" id="inputGroupFileAddon04">Upload Excel</button>
                <a href="{{ asset('template_siswa.xlsx') }}" class="btn btn-outline-secondary btn-sm" target="_blank">Download Template</a>
                {!! Form::close() !!}
                <div class="alert alert-info mt-3" role="alert">
                    Silahkan download template excel, jangan mengubah urutan kolom, isi data sesuai dengan template.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ $siswaKelasChart->cdn() }}"></script>
{{ $siswaKelasChart->script() }}
@endsection
