@extends('layouts.app_sneat', ['title' => 'Pengaturan'])

@section('content')
    @include('operator.setting_menu')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">


                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>Pengaturan Whatsapp</h5>
                            <div class="form-group mt-3">
                                Status Device:
                                <span class="badge bg-{{ $statusKoneksiWa ? 'primary' : 'danger' }}">
                                    {{ $statusKoneksiWa ? 'Connected' : 'Not Connected' }}
                                </span>
                            </div>
                            @if ($statusKoneksiWa)
                                <div class="form-group mt-3">
                                    Nomor Whatsapp : {{ $deviceInfo->sender }}
                                </div>
                                <div class="form-group mt-3">
                                    Paket Kadaluarsa sampai : {{ $deviceInfo->expired_date }}
                                </div>
                                <div class="form-group mt-3">
                                    Kuota pengiriman pesan : {{ $deviceInfo->quota }}
                                </div>
                                {!! Form::open([
                                    'route' => 'settingwhacenter.store',
                                    'method' => 'POST',
                                    'files' => true,
                                ]) !!}
                                <div class="form-group mt-3">
                                    <label for="number">Nomor whatsapp tujuan</label>
                                    {!! Form::text('number', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Masukkan nomor. misal: 628977571654',
                                    ]) !!}
                                    <span class="text-danger">{{ $errors->first('number') }}</span>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="message">Pesan</label>
                                    {!! Form::text('message', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Masukkan pesan',
                                    ]) !!}
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                </div>
                                {!! Form::submit('Test Kirim Pesan', ['class' => 'btn btn-success mt-2 mr-2']) !!}
                            @endif
                            {!! Form::close() !!}
                            @if ($statusKoneksiWa == false)
                                <a href="{{ $url }}" class="btn btn-primary mt-5">Sambungkan</a>
                                <p class="mt-2">*Masukkan terlebih dahulu Token WaBlas pada bagian menu Token</p>
                            @else
                                <a href="{{ route('disconnect') }}" class="btn btn-danger mt-3">Putuskan</a>
                            @endif
                        </div>
                    </div>
                    {{-- {!! Form::close() !!} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
