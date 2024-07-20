@extends('layouts.app_sneat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">{{ $title }}</h5>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                        <button class="btn btn-secondary btn-sm" id="btn-div" data-bs-toggle="modal" data-bs-target="#importModal">Import Dari File Excel</button>
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                        <div class="input-group mt-3">
                            <input name="q" class="form-control" placeholder="Cari Nama Orang Tua Siswa"
                                   aria-label="cari nama" aria-describedby="button-addon2" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="{{ config('app.table_style') }}">
                        <thead class="{{ config('app.thead_style') }}">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No.HP</th>
                            <th>Email</th>
                            <th>Akses</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($models as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->nohp }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->akses }}</td>
                                <td>
                                    {!! Form::open([
                                        'route' => [$routePrefix . '.destroy', $item->id],
                                        'method' => 'DELETE',
                                        'onsubmit' => 'return confirm("Yakin ingin menghapus data ini?")',
                                    ]) !!}
                                    <a href="{{ route($routePrefix . '.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-info"></i> Detail
                                    </a>
                                    <a href="{{ route($routePrefix . '.edit', $item->id) }}" class="btn btn-warning btn-sm mx-2">
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
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Orang Tua Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'waliimport.store', 'method' => 'POST', 'files' => true]) !!}
                <div class="mb-3">
                    <label for="inputGroupFile04" class="form-label">Upload File (Excel)</label>
                    <input type="file" name="template" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                </div>
                <button class="btn btn-primary btn-sm" type="submit" id="inputGroupFileAddon04">Upload Excel</button>
                <a href="{{ asset('template_orangtua.xlsx') }}" class="btn btn-outline-secondary btn-sm" target="_blank">Download Template</a>
                {!! Form::close() !!}
                <div class="alert alert-info mt-3" role="alert">
                    Silahkan download template excel, jangan mengubah urutan kolom, isi data sesuai dengan template.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
