@extends('layouts.app_sneat')

@section('content')
    <div class="bs-stepper wizard-numbered mt-2">
        @include('operator.tagihanlain_stepheader')

        <div class="bs-stepper-content">
            <div id="account-details" class="content active dstepper-block">
                <div class="content-header mb-3">
                    <h6 class="mb-0">Tagihan Untuk</h6>
                    <small>Tagihan ini dibuat untuk siapa</small>
                </div>

                {!! Form::open([
                    'route' => ['tagihanlainstep.create'],
                    'method' => 'GET',
                ]) !!}
                {!! Form::hidden('step', 2, []) !!}
                
                <div class="row g-3">
                    <div class="col-sm-6 mb-3">
                        <div class="form-check">
                            {!! Form::radio('tagihan_untuk', 'semua', true, [
                                'class' => 'form-check-input',
                                'id' => 'defaultRadio1'
                            ]) !!}
                            <label class="form-check-label" for="defaultRadio1">
                                Semua Siswa
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                {!! Form::radio('tagihan_untuk', 'pilihan', true, [
                                    'class' => 'form-check-input',
                                    'id' => 'defaultRadio2'
                                ]) !!}
                                <label class="form-check-label" for="defaultRadio2">
                                    Pilih Siswa Tertentu
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-between">
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
