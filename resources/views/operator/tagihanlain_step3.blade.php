@extends('layouts.app_sneat')

@section('content')
    <div class="bs-stepper wizard-numbered mt-2">
        @include('operator.tagihanlain_stepheader')
        <div class="bs-stepper-content">
            <div id="account-details" class="content active dstepper-block">
                <div class="content-header mb-3">
                    @if (session('tagihan_untuk') == 'semua')
                        <h6 class="mb-0">Tagihan Untuk Semua Siswa</h6>
                    @else
                        <h6 class="mb-0">Tagihan Untuk {{ session('data_siswa')->count() }} Siswa</h6>
                    @endif

                    <small>Pilih Biaya yang akan ditagihkan. </small>
                </div>

                {!! Form::open([
                    'route' => ['tagihanlainstep.create'],
                    'method' => 'GET',
                ]) !!}
                {!! Form::hidden('step', 4, []) !!}
                
                <div class="row g-3">
                   <div class="form-group">
                        <label for="biaya_id">Pilih Biaya atau 
                            <a href="{{ route('biaya.create') }}" target="blank">Buat Baru</a>
                        </label>
                        {!! Form::select('biaya_id', $biayaList, null, [
                            'class' => 'form-control select2'
                        ]) !!}
                   </div>
                   <div class="alert alert-secondary" role="alert">
                        Jika anda menambahkan biaya baru, jangan lupa klik tombol berikut untuk merefresh halaman ini.
                        <a href="">refresh</a>
                   </div>
                </div>

                <div class="col-12 d-flex justify-content-between">
                    <a href="{{ route('tagihanlainstep.create', ['step' => 2]) }}" class="btn btn-label-secondary btn-prev">
                        <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                        <span class="align-middle d-sm-inline-block d-none">Back</span>
                    </a>                    
                    <button class="btn btn-primary btn-next" type="submit">
                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                        <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
