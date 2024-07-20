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
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                        <div class="input-group mt-6">
                            <input name="q" class="form-control" placeholder="Cari Data"
                                aria-label="cari data" aria-describedby="button-addon2" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive mt-3 mb-3">
                    <table class="{{ config('app.table_style') }}">
                        <thead class="{{ config('app.thead_style') }}">
                            <tr>
                                <th width="1%">No</th>
                                <th>ID Biaya</th>
                                <th>Nama Biaya</th>
                                <th>Total Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($models as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge bg-primary"></span>{{ $item->id }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ formatRupiah($item->total_tagihan) }}</td>
                                <td>
                                    <div class="d-flex mb-2">
                                        <div class="mr-2">
                                            <a href="{{ route($routePrefix .'.create', [
                                                'parent_id' => $item->id,
                                            ]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i> Item Biaya
                                            </a>
                                        </div>
                                        <div class="mr-2">
                                            <a href="{{ route($routePrefix . '.edit', $item->id) }}" class="btn btn-warning btn-sm mx-2">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </div>
                                        <div class="mr-2">
                                            {!! Form::open([
                                                'route' => [$routePrefix . '.destroy', $item->id],
                                                'method' => 'DELETE',
                                                'onsubmit' => 'return confirm("Yakin ingin menghapus data ini?")',
                                            ]) !!}
                                            <button type="submit" class="btn btn-danger btn-sm mx-2">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Tidak Ada</td>
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
