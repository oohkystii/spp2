@extends('layouts.app_sneat', ['title' => 'Pengaturan'])
@section('content')
    @include('operator.setting_menu')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Pengaturan Aplikasi</h3>
                    {!! Form::open([
                        'route' => 'setting.store',
                        'method' => 'POST',
                        'files' => true,
                    ]) !!}

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="form-group mt-3">
                                <label for="app_pagination">Data Per Halaman</label>
                                {!! Form::number('app_pagination', settings('app_pagination'), ['class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('app_pagination') }}</span>
                            </div>
                            {{-- <div class="form-group mt-3">
                                <label for="no_wa_operator">Nomor Whatsapp Operator / Penanggung Jawab Aplikasi (contoh: 628977571654)</label>
                                {!! Form::number('no_wa_operator', settings('no_wa_operator'), ['class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('no_wa_operator') }}</span>
                            </div> --}}
                            <hr />
                            {{-- <div class="form-group mt-3">
                                <label for="pesan_bulan">Kirim Pesan Whatsapp Otomatis Pengingat Tagihan Setiap Tanggal/bulan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Tanggal dan Jam Kirim Otomatis</span>
                                    {!! Form::selectRange('tanggal_ke', 1, 31, settings('tanggal_ke'), ['class' => 'form-control', 'placeholder' => 'Pilih Tanggal Kirim Otomatis']) !!}
                                    {!! Form::time('jam_ke', settings('jam_ke'), ['class' => 'form-control', 'placeholder' => 'Pilih Jam Kirim Otomatis']) !!}
                                </div>
                            </div> --}}
                            <div class="form-group mt-3">
                                <div class="alert alert-secondary text-black" role="alert">
                                    <strong>Format / Template Pesan Whatsapp Otomatis:</strong>
                                    <div>{bulan}: Bulan tagihan</div>
                                    <div>{tahun}: Tahun tagihan</div>
                                    <div>{nama}: Nama Siswa</div>
                                    <div>{jumlah_biaya}: Total Tagihan</div> <!-- Menampilkan total tagihan -->
                                    <div>{jatuh-tempo}: Tanggal jatuh tempo</div>
                                    <hr>
                                    <div class="fw-bold">Contoh Template :</div>
                                    <div>
                                        <i>
                                            Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu.
                                            Berikut kami informasikan Tagihan Biaya Sekolah Bulan {bulan} {tahun} atas nama {nama} dengan jumlah tagihan {jumlah_biaya}.
                                            Terima kasih.
                                        </i>
                                    </div>
                                    <div class="fw-bold">Pesan yang akan terkirim :</div>
                                    <div>
                                        <i>
                                            Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu. <br> Berikut kami
                                            informasikan
                                            Tagihan Biaya Sekolah Bulan {{ date('F') }} {{ date('Y') }} atas nama Kysti
                                            Qoriah dengan jumlah tagihan 100.000. <br> Terima
                                            kasih.
                                        </i>
                                    </div>
                                </div>
                                <label for="template_pengingat_tagihan">Format / Template Pesan Whatsapp Otomatis</label>
                                {!! Form::textarea('template_pengingat_tagihan', $message, [
                                    'class' => 'form-control',
                                    'rows' => 3,
                                ]) !!}
                                <span class="text-danger">{{ $errors->first('template_pengingat_tagihan') }}</span>
                            </div>
                            {!! Form::submit('UPDATE', ['class' => 'btn btn-primary mt-2']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
