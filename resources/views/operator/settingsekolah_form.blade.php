@extends('layouts.app_sneat', ['title' => 'Pengaturan'])
@section('content')
    @include('operator.setting_menu')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => 'settingsekolah.store',
                        'method' => 'POST',
                        'files' => true, // tambahkan ini untuk mengizinkan upload file
                        'enctype' => 'multipart/form-data', // tambahkan enctype untuk form upload file
                    ]) !!}
                    <h5>Pengaturan Sekolah</h5>
                    <div class="form-group">
                        <label for="app_logo">Logo Sekolah (format: jpg, png, jpeg, ukuran file maks: 5MB)</label>
                        {!! Form::file('app_logo', ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('app_logo') }}</span> <br />
                        @if(settings()->get('app_logo'))
                            <img src="{{ Storage::url(settings()->get('app_logo')) }}" width="200">
                        @else
                            <p>Belum ada logo yang diunggah.</p>
                        @endif
                    </div>
                    <div class="form-group mt-3">
                        <label for="app_name">Nama Sekolah</label>
                        {!! Form::text('app_name', settings()->get('app_name'), ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('app_name') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="app_email">Email Sekolah</label>
                        {!! Form::text('app_email', settings()->get('app_email'), ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('app_email') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="app_phone">Nomor Telepon Sekolah</label>
                        {!! Form::text('app_phone', settings()->get('app_phone'), ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('app_phone') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="app_address">Alamat Sekolah</label>
                        {!! Form::textarea('app_address', settings()->get('app_address'), [
                            'class' => 'form-control',
                            'rows' => '3',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('app_address') }}</span>
                    </div>
                    {!! Form::submit('UPDATE', ['class' => 'btn btn-primary mt-2']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
