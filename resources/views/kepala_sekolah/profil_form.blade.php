@extends('layouts.app_sneat_kepala_sekolah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ $title }}</h5>
            </div>
            <div class="card-body">
                {!! Form::model($model, ['route' => $route, 'method' => $method]) !!}
                <div class="form-group">
                    <label for="name">Nama</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'autofocus']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
                <div class="form-group mt-3">
                    <label for="nohp">No HP</label>
                    {!! Form::text('nohp', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{{ $errors->first('nohp') }}</span>
                </div>
                @if (\Route::is('user.create'))
                <div class="form-group mt-3">
                    <label for="akses">Hak Akses</label>
                    {!! Form::select(
                        'akses',
                        [
                            'operator' => 'Operator',
                            'kepala_sekolah' => 'Kepala Sekolah',
                        ],
                        null,
                        ['class' => 'form-control']
                    ) !!}
                    <span class="text-danger">{{ $errors->first('akses') }}</span>
                </div>
                @endif
                <div class="form-password-toggle mt3">
                    <label class="form-label" for="basic-default-password32">Password</label>
                    <div class="input-group input-group-merge">
                        <input name="password" type="password" class="form-control" id="basic-default-password32"
                        placeholder=". . . . . ." aria-describedby="basic-default-password">
                        <span class="input-group-text cursor-pointer" id="basic-default-password">
                            <i class="bx bx-hide"></i>
                        </span>
                    </div>
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
                @if ($model->akses)
                    {!! Form::hidden('akses', $model->akses) !!}
                @endif
                {!! Form::submit($button, ['class' => 'btn btn-primary mt-3']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
