@extends('layouts.app_sneat')
@section('js')
    <script>
        $(document).ready(function() {
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
                    {!! Form::model($model, ['route' => $route, 'method' => $method, 'id' => 'form-ajax']) !!}
                    <div class="form-group">
                        <label for="siswa_id">Pilih Siswa atau Biarkan Kosong</label>
                        {!! Form::select('siswa_id', $siswaList, null, [
                            'class' => 'form-control select2',
                            'placeholder' => 'Pilih Siswa',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('siswa_id') }}</span>
                    </div>
                    <div class="row my-2">
                        <div class="col-md-4 form-group">
                            <label for="tanggal_tagihan">Tanggal Tagihan</label>
                            {!! Form::date('tanggal_tagihan', $model->tanggal_tagihan ?? date('Y-m-d'), ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('tanggal_tagihan') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group mt-3">
                            <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                            {!! Form::date('tanggal_jatuh_tempo', $model->tanggal_jatuh_tempo ?? date('Y-m-d'), ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('tanggal_jatuh_tempo') }}</span>
                        </div>
                        <div class="col-md-4 form-group mt-3">
                            <label for="tanggal_pemberitahuan">Tanggal Pemberitahuan</label>
                            <input class="form-control" type="datetime-local" id="tanggal_pemberitahuan"
                                name="tanggal_pemberitahuan"
                                value="<?= $model->tanggal_pemberitahuan ?? date('Y-m-d H:i') ?>" />

                            <span class="text-danger">{{ $errors->first('tanggal_pemberitahuan') }}</span>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="keterangan">Keterangan</label>
                        {!! Form::textarea('keterangan', null, ['class' => 'form-control', 'rows' => 3]) !!}
                        <span class="text-danger">{{ $errors->first('keterangan') }}</span>
                    </div>
                    {!! Form::submit($button, ['class' => 'btn btn-primary mt-3']) !!}
                    {{-- <button class="btn btn-primary mt-3" type="submit">
                        <span id="loading-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ $button }}
                    </button> --}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
