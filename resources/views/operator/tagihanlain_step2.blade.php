@extends('layouts.app_sneat')
@section('js')
    <script>
        $(document).ready(function () {
            $("#checkAll").change(function() {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
    </script>
@endsection
@section('content')
    <div class="bs-stepper wizard-numbered mt-2">
        @include('operator.tagihanlain_stepheader')

        <div class="bs-stepper-content">
            <div id="account-details" class="content active dstepper-block">
                <div class="content-header mb-3">
                    <h6 class="mb-0">Cari Data Siswa</h6>
                    <small>Cari Data siswa berdasarkan:</small>
                </div>

                {!! Form::open([
                    'url' => request()->fullUrl(),
                    'method' => 'GET',
                ]) !!}
                {!! Form::hidden('step', 2) !!}
                {!! Form::hidden('tagihan_untuk', request('tagihan_untuk')) !!}
                
                <div class="g-3">
                    <div class="form-group mt-3">
                        <label for="nama">Nama</label>
                        {!! Form::text('nama', request('nama'), [
                            'class' => 'form-control',
                            'autofocus',
                            'placeholder' => 'Cari berdasarkan nama',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('nama') }}</span>
                    </div>

                    <div class="form-group mt-3">
                        <label for="kelas">Kelas</label>
                        {!! Form::select('kelas', getNamaKelas(), request('kelas'), [
                            'class' => 'form-control',
                            'placeholder' => 'Pilih kelas atau tampilkan semua',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('kelas') }}</span>
                    </div>

                    <div class="form-group mt-3">
                        <label for="angkatan">Angkatan</label>
                        {!! Form::selectRange('angkatan', 2023, date('Y') + 1, request('angkatan'), [
                            'class' => 'form-control',
                            'placeholder' => 'Pilih Angkatan atau tampilkan semua',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('angkatan') }}</span>
                    </div>

                    <input type="submit" name="cari" value="Cari Data" class="btn btn-primary mt-3 mb-5">
                    <hr>
                    {!! Form::close() !!}
                    @if(Session::has('data_siswa'))
                        <h5>DATA SISWA YANG AKAN DITAGIH: {{ Session::get('data_siswa')->count() }}</h5>
                       <ul>
                        @foreach (Session::get('data_siswa') as $item)
                            <a href="{{ route('tagihanlainstep2.delete', [
                                'id' => $item->id,
                                'action' => 'delete',
                             ]) }}">x</a>
                            <span class="badge bg-secondary">
                                ({{ $item->nisn }}) {{ $item->nama }}
                            </span>
                        @endforeach
                       </ul>
                       <a href="{{ route('tagihanlainstep2.delete', ['action' => 'deleteall']) }}">Hapus Semua</a>
                    @endif
                    <hr>
                    @if(request()->filled('cari'))
                        <h5>PILIH DATA SISWA</h5>
                        {!! Form::open([
                            'route' => 'tagihanlainstep2.store',
                            'method' => 'POST',
                        ]) !!}
                        
                        <div class="table-responsive mb-3 mt-3">
                            <table class="{{ config('app.table_style') }}">
                                <thead class="{{ config('app.thead_style') }}">
                                    <tr>
                                        <th width="1%">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Angkatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $item)
                                        <tr>
                                            <td>{!! Form::checkbox('siswa_id[]', $item->id, $item->checked, []) !!}</td>
                                            <td>
                                                <div>
                                                    <a href="{{ route('kartuspp.index', [
                                                        'siswa_id' => $item->id,
                                                        'tahun' => date('Y'),
                                                    ]) }}" class="" target="blank">
                                                        <i class="fa fa-id-card"></i>
                                                    </a> {{ $item->nama }}
                                                </div>
                                                <div>{{ $item->nisn }}</div>
                                            </td>
                                            <td>{{ $item->kelas }}</td>
                                            <td>{{ $item->angkatan }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Data Tidak Ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <input type="submit" value="Pilih Siswa" class="btn btn-primary">
                        </div>
                        {!! Form::close() !!}
                    @endif

                    <div class="alert alert-secondary" role="alert">
                        Silahkan pilih siswa yang akan ditagih, klik tombol Pilih Siswa untuk menyimpan data, jika sudah selesai klik tombol Next.
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <a href="{{ route('tagihanlainstep.create', ['step' => 1]) }}" class="btn btn-label-secondary btn-prev">
                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                            <span class="align-middle d-sm-inline-block d-none">Back</span>
                        </a>

                        <a href="{{ route('tagihanlainstep.create', ['step' => 3]) }}" class="btn btn-primary btn-next" type="submit">
                            <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
