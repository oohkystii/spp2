@extends('layouts.app_sneat')

@section('js')
    <script>
        $(document).ready(function() {
            $('#show-all').click(function(e) {
                $('.more').removeClass('d-none');
            });
        });
    </script>
@endsection

@section('content')
    <div class="bs-stepper wizard-numbered mt-2">
        @include('operator.tagihanlain_stepheader')
        <div class="bs-stepper-content">
            <div id="account-details" class="content active dstepper-block">
                {!! Form::open([
                    'route' => ['tagihanlainstep4.store'],
                    'method' => 'POST',
                ]) !!}
                {!! Form::hidden('step', 4, []) !!}
                {!! Form::hidden('biaya_id', $biaya->id, []) !!}
                <div class="row mt-2 p-3">
                    <div class="form-group">
                        <label for="tanggal_tagihan">Tanggal Tagihan</label>
                        {!! Form::date('tanggal_tagihan', date('Y-m-d'), ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('tanggal_tagihan') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                        {!! Form::date('tanggal_jatuh_tempo', date('Y-m-d'), ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('tanggal_jatuh_tempo') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="tanggal_pemberitahuan">Tanggal Pemberitahuan</label>
                        {!! Form::datetimeLocal('tanggal_pemberitahuan', date('Y-m-d\TH:i'), ['class' => 'form-control', 'id' => 'tanggal_pemberitahuan']) !!}
                        <span class="text-danger">{{ $errors->first('tanggal_pemberitahuan') }}</span>
                    </div>
                    Tagihan ini dibuat untuk: {{ $siswa->count() }} Siswa
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama</th>
                                <th>NISN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $item)
                                <tr class="{{ $loop->iteration >= 5 ? 'd-none more' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nisn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($siswa->count() >= 5)
                        <a href="#" id="show-all">Lihat Selengkapnya</a>
                    @endif
                    <hr>
                    <div>
                        Biaya yang ditagihkan adalah: {{ $biaya->nama }} Total {{ formatRupiah($biaya->total_tagihan) }}
                    </div>
                    <div class="ml-2">
                        <ul>
                            @foreach ($biaya->children as $item)
                                <li>{{ $item->nama . ' ' . formatRupiah($item->jumlah) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="alert alert-secondary" role="alert">
                        Tekan tombol simpan untuk memproses tagihan ini.
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <a href="{{ route('tagihanlainstep.create', ['step' => 3]) }}"
                        class="btn btn-label-secondary btn-prev">
                        <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                        <span class="align-middle d-sm-inline-block d-none">Back</span>
                    </a>
                    <button class="btn btn-primary btn-next" type="submit">
                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Simpan</span>
                        <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
