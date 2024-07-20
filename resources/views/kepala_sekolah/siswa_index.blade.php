@extends('layouts.app_sneat_kepala_sekolah')

@section('js')
<script>
    $(document).ready(function() {
        $("#div-import").hide();
        $("#btn-div").click(function(e) {
            $("#div-import").toggle();
        });
    });
</script>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ $title }}</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-end mb-3">
                    <div class="col-md-6">
                        {!! Form::open(['route' => 'kepala_sekolah.siswa.index', 'method' => 'GET']) !!}
                        <div class="input-group">
                            <input name="q" class="form-control" placeholder="Cari Nama Siswa"
                                aria-label="cari nama" aria-describedby="button-addon2" value="{{ request('q') }}">
                            <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                <i class="bx bx-search"></i> Cari
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>                
                <div class="table-responsive">
                    <table class="{{ config('app.table_style') }}">
                        <thead class="{{ config('app.thead_style') }}">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Wali</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Angkatan</th>
                                <th>Biaya SPP</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($models as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->wali->name ?? 'Belum Ada' }}</td>
                                <td>
                                    <div><a href="{{ route('kartuspp.index', [
                                        'nisn' => $item->id,
                                        'tahun' => date('Y'),
                                    ]) }}"
                                            class="" target="_blank"><i class="fa fa-id-card"></i>
                                        </a> {{ $item->nama }}</div>
                                    <div>{{ $item->nisn }}</div>
                                </td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->angkatan }}</td>
                                <td>{{ formatRupiah($item->biaya?->first()->total_tagihan) }}</td>
                                {{-- <td>
                                    <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-info"></i> Detail
                                    </a>
                                </td>                                 --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Tidak Ada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {!! $models->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
