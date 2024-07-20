@extends('layouts.app_sneat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-body">
                    {!! Form::model($model, ['route' => $route, 'method' => $method, 'files' => true]) !!}
                    <div class="form-group">
                        <label for="wali_id">Orang Tua Murid (Optional)</label>
                        {!! Form::select('wali_id', $wali, null, [
                            'class' => 'form-control select2', 
                            'placeholder' => 'Pilih Nama Orang Tua',
                        ]) !!}
                        <span class="text-danger">{{ $errors->first('wali_id') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="nama">Nama</label>
                        {!! Form::text('nama', null, ['class' => 'form-control', 'autofocus']) !!}
                        <span class="text-danger">{{ $errors->first('nama') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="biaya_id">Biaya Tagihan</label>
                        {!! Form::select('biaya_id', $listBiaya, null, ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('biaya_id') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="nisn">NISN</label>
                        {!! Form::text('nisn', null, ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('nisn') }}</span>
                    </div>
                    {!! Form::hidden('jurusan', 0, []) !!}
                    {{-- <div class="form-group mt-3">
                        <label for="jurusan">Jurusan</label>
                        {!! Form::select('jurusan', getNamaJurusan(), null, ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('jurusan') }}</span>
                    </div> --}}
                    <div class="form-group mt-3">
                        <label for="kelas">Kelas</label>
                        {!! Form::select('kelas', getNamaKelas(), null, ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('kelas') }}</span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="angkatan">Angkatan</label>
                        {!! Form::selectRange('angkatan',2023, date('Y') + 1, null, ['class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('angkatan') }}</span>
                    </div>
                    @if ($model->foto != null)
                        <div class="m-3">
                            <img src="{{ \Storage::url($model->foto) }}" alt="" width="200" class="img-thumbnail">
                        </div>   
                    @endif
                    <div class="form-group mt-3">
                        <label for="foto">Foto <b>(Format: jpg, png, jpeg, Ukuran Maks: 5MB)</b></label>
                        {!! Form::file('foto', ['class' => 'form-control', 'accept' => 'image/*']) !!}
                        <span class="text-danger">{{ $errors->first('foto') }}</span>
                    </div>
                    {!! Form::submit($button, ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
