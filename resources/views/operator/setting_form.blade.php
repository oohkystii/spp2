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
                            <hr />
                            <div class="form-group mt-3">
                                <div class="alert alert-secondary text-black" role="alert">
                                    <strong>Format / Template Pesan Whatsapp Otomatis:</strong>
                                    <div>{bulan}: Bulan tagihan</div>
                                    <div>{tahun}: Tahun tagihan</div>
                                    <div>{nama}: Nama Siswa</div>
                                    <div>{jumlah_biaya}: Total Tagihan</div>
                                    <div>{jatuh-tempo}: Tanggal jatuh tempo</div>
                                    <div>{nama_biaya}: Nama Tagihan</div>
                                    <div>{total_dibayar}: Total yang sudah dibayar (hanya untuk status "angsur")</div>
                                    <div>{sisa_pembayaran}: Sisa pembayaran (hanya untuk status "angsur")</div>
                                    <hr>
                                    <div class="fw-bold">Contoh Template:</div>
                                    <div>
                                        <i>
                                            Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu.<br>
                                            Berikut kami informasikan Tagihan {nama_biaya} Bulan {bulan} {tahun} atas nama {nama}
                                            dengan jumlah tagihan {jumlah_biaya}. Terima kasih.
                                        </i>
                                    </div>
                                    <hr>
                                    <div class="fw-bold">Pesan yang akan terkirim:</div>
                                    <div>
                                        <i>
                                            Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu.<br>
                                            Berikut kami informasikan Tagihan SPP Bulan {{ date('F') }} {{ date('Y') }} atas nama
                                            Kysti Qoriah dengan jumlah tagihan 100.000. Terima kasih.
                                        </i>
                                    </div>
                                </div>

                                <label for="template_pengingat_tagihan">Format / Template Pesan Whatsapp Otomatis - Baru</label>
                                {!! Form::textarea('template_pengingat_tagihan', settings('template_pengingat_tagihan'), [
                                    'class' => 'form-control',
                                    'rows' => 3,
                                ]) !!}
                                <span class="text-danger">{{ $errors->first('template_pengingat_tagihan') }}</span>

                                <label for="template_pengingat_tagihan_angsur">Format / Template Pesan Whatsapp Otomatis - Angsur</label>
                                {!! Form::textarea('template_pengingat_tagihan_angsur', settings('template_pengingat_tagihan_angsur'), [
                                    'class' => 'form-control',
                                    'rows' => 3,
                                ]) !!}
                                <span class="text-danger">{{ $errors->first('template_pengingat_tagihan_angsur') }}</span>
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
