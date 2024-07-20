@extends('layouts.app_sneat', ['title' => 'Pengaturan'])
@section('content')
    @include('operator.setting_menu')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Setting Token</h3>
                    {!! Form::open([
                        'route' => 'settingtoken.store',
                        'method' => 'POST',
                        'files' => true,
                    ]) !!}

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="form-group mt-3">
                                <label for="token">Token Device Whatsapp</label>
                                <input type="text" name="token" id="token" class="form-control" value=<?= $token ?>>
                                <span class="text-danger">{{ $errors->first('token') }}</span>
                            </div>
                            <hr />
                            {!! Form::submit('UPDATE', ['class' => 'btn btn-primary mt-2']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
