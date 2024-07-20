@extends('layouts.app_sneat')
@section('js')
<script>
    $(document).ready(function() {
        $("#btn-lunas").hide();
    
        $(".check-tagihan-id").change(function(e) {
            if ($(this).prop('checked')) {
                $("#btn-lunas").show();
            }
            if ($(".check-tagihan-id:checked").length == 0) {
                $("#btn-lunas").hide();
            }
        });
    
        $("#checked-all").click(function(e) {
            if ($(this).is(':checked')) {
                $("#btn-lunas").show();
                $(".check-tagihan-id").prop('checked', true);
            } else {
                $("#btn-lunas").hide();
                $('.check-tagihan-id').prop('checked', false);
            }
        });
        $('#btn-lunas').click(function (e) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                type: "POST",
                url: "{{ route('tagihanupdate.lunas') }}",
                data: $(".check-tagihan-id").serialize(),
                dataType: "json",
                beforeSend: function() {
                    $("#loading-spinner").show();
                        $("#loading-overlay").removeClass('d-none');
                    },
                    success: function (response) {
                        $("#alert-message").removeClass('d-none');
                        $("#alert-message").html(response.message);                        
                        $("#loading-spinner").hide();
                        $("#loading-overlay").addClass('d-none');
                    },
                    error: function (xhr, status, error) { 
                        $("#alert-message").removeClass('d-none');
                        $("#alert-message").removeClass('alert-success');
                        $("#alert-message").addClass('alert-danger');
                        $("#alert-message").html(xhr.responseJSON.message);
                        $("#loading-overlay").addClass('d-none');
                        $("#Loading-spinner").hide();
                    }
                });
            });
            $("#loading-spinner").hide();
    });
    </script>
    
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                            <div class="row justify-content-end gx-2 mx-4">
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => 'Pencarian Data Siswa']) !!}
                                </div>
                                {{-- <div class="col-md-2 col-sm-12">
                                    {!! Form::select('biaya', $biayaList, request('biaya_id'), [
                                        'class' => 'form-control',
                                        'placeholder' => 'Pilih Biaya',
                                    ]) !!}
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    {!! Form::select('kelas', getNamaKelas(), request('kelas'), [
                                        'class' => 'form-control',
                                        'placeholder' => 'Pilih Kelas',
                                    ]) !!}
                                </div> --}}
                                <div class="col-md-2 col-sm-12">
                                    {!! Form::select('status', 
                                        [
                                            '' => 'Pilih Status',
                                            'lunas' => 'Lunas',
                                            'baru' => 'Baru',
                                            'angsur' => 'Angsur',
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
                                <div class="col-md-1 col-sm-12">
                                    <button class="btn btn-primary btn-block" type="submit">Tampil</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>                        
                    </div>
                    <button type="button" id="btn-lunas" class="btn btn-success mt-3">UBAH MENJADI LUNAS</button>
                    <div class="table-responsive mt-3">
                        <table class="{{ config('app.table_style') }}">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th><input type="checkbox" id="checked-all"></th>
                                    <th>Nama</th>
                                    <th>Tanggal Tagihan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Total Tagihan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($models as $item)
                                    <tr>
                                        <td>{!! Form::checkbox('tagihan_id[]', $item->id, null, ['class' => 'check-tagihan-id']) !!}</td>
                                        <td>
                                            <div>{{ $item->siswa->nisn }}</div>
                                            <a href="{{ route('kartuspp.index', [
                                                'siswa_id' => $item->siswa_id,
                                                'tahun' => getTahunAjaran(),
                                            ]) }}"
                                                class="" target="blank"><i class="fa fa-id-card"></i>
                                            </a> {{ $item->siswa->nama }}
                                        <td>{{ $item->tanggal_tagihan->translatedFormat('d-M-Y') }}</td>
                                        <td>{{ $item->biaya->nama }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status_style }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>{{ formatRupiah($item->tagihanDetails->sum('jumlah_biaya')) }}</td>
                                        <td>
                                            {!! Form::open([
                                                'route' => [$routePrefix . '.destroy', $item->id],
                                                'method' => 'DELETE',
                                                'onsubmit' => 'return confirm("Yakin ingin menghapus data ini?")',
                                            ]) !!}
                                            <a href="{{ route($routePrefix . '.show', [
                                                $item->id,
                                                'siswa_id' => $item->siswa_id,
                                                'bulan' => $item->tanggal_tagihan->format('m'),
                                                'tahun' => $item->tanggal_tagihan->format('Y'),
                                            ]) }}" class="btn btn-info btn-sm mx-2">
                                                <i class="fa fa-info"></i> Detail
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
                        <div class="mt-3">
                            {!! $models->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection