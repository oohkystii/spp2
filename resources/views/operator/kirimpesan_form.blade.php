@extends('layouts.app_sneat', ['title' => 'Pengaturan'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => 'kirimpesan.store',
                        'method' => 'POST',
                        'files' => true,
                    ]) !!}
                    
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>KIRIM PESAN MASSAL</h5>
                            <div class="form-group mt-3">
                                <label for="wali_id">Pilih wali murid atau kosongkan untuk mengirim ke semua wali murid:</label>
                                {!! Form::select('wali_id', $waliList, null, [
                                    'class' => 'form-control select2',
                                    'placeholder' => 'Pilih wali atau kosongkan',
                                ]) !!}
                                <span class="text-danger">{{ $errors->first('wali_id') }}</span>
                            </div>
                            <div class="form-group mt-3">
                                <label for="pesan">Ketik Pesan yang akan dikirim:</label>
                                {!! Form::textarea('pesan', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                <span class="text-danger">{{ $errors->first('pesan') }}</span>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="channels">Pilih Channel Notifikasi</label>
                                <div class="form-check mt-3">
                                    <input name="channels[]" class="form-check-input" type="checkbox" value="whatsapp" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">Whatsapp</label>
                                </div>
                                <div class="form-check mt-3">
                                    <input name="channels[]" class="form-check-input" type="checkbox" value="mail" id="defaultCheck2">
                                    <label class="form-check-label" for="defaultCheck2">Email</label>
                                </div>
                            </div>                            
                            {!! Form::submit('SIMPAN', ['class' => 'btn btn-primary mt-2']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
