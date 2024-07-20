@extends('layouts.app_sneat', ['title' => 'Pengaturan'])
<style>
    /* CSS untuk canvas */
    #signatureCanvas {
        border: 1px solid #000;
    }
</style>

{{-- Signature Pad --}}
<link rel="stylesheet" href="{{ asset('js/vendor/input-signature/assets/jquery.signaturepad.css') }}">
@section('content')
    @include('operator.setting_menu')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => 'settingbendahara.store',
                        'method' => 'POST',
                        'files' => true,
                    ]) !!}
                    
                    <div class="card-body">
                        <h5>Pengaturan Penanggung Jawab atau Bendahara</h5>
                        <div class="alert alert-info" role="alert">
                            Data penanggung jawab yang diinput di form ini akan tampil di kwintansi, invoice dan kartu spp.
                        </div>
                        <div class="form-group mt-3">
                            <label for="pj_nama">Nama Penanggung Jawab (ex:bendahara)</label>
                            {!! Form::text('pj_nama', settings()->get('pj_nama'), ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('pj_nama') }}</span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="pj_jabatan">Nama Jabatan (ex:bendahara)</label>
                            {!! Form::text('pj_jabatan', settings()->get('pj_jabatan'), ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('pj_jabatan') }}</span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="pj_ttd">Tanda Tangan Digital</label>
                            <div class="sigPad" style="width: 100%;max-width:315px;">
                                <div class="sig sigWrapper" style="height: 100%;">
                                    <canvas class="pad" width="315" height="110"></canvas>
                                    <input type="hidden" name="output" class="output"
                                        value="{{ settings()->get('pj_ttd_o') }}">
                                </div>
                            </div>
                            <a href="javascript:;"onclick="clearSignature()">Reset</a>
            
                            <input type="hidden" name="img_signature_base64" value="{{ old('img_signature_base64') }}">
                        </div>
                        {!! Form::submit('UPDATE', ['class' => 'btn btn-primary mt-3']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Signature Pad --}}
    <script src="{{ asset('js/vendor/input-signature/assets/json2.min.js') }}"></script>
    <script src="{{ asset('js/vendor/input-signature/assets/numeric-1.2.6.min.js') }}"></script>
    <script src="{{ asset('js/vendor/input-signature/assets/bezier.js') }}"></script>
    <script src="{{ asset('js/vendor/input-signature/jquery.signaturepad.js') }}"></script>

    <script>
        let oldSignature = `{{ settings()->get('pj_ttd_o') }}`;

        let signatureElement = $('.sigPad').signaturePad({
            drawOnly: true,
            drawBezierCurves: true,
            lineTop: 200,
            bgColour: 'transparent',
            errorMessageDraw: 'Tanda tangan wajib diisi.',
            penColour: '#000000',
            onDrawEnd: function() {
                cekResultSignature()
            }
        });

        if (oldSignature) {
            oldSignature = $.parseJSON(oldSignature.replaceAll('&quot;', '"'));
            signatureElement.regenerate(oldSignature ?? null);
        }

        const cekResultSignature = () => {
            let sigImage = signatureElement.getSignatureImage();
            let sigDefault = signatureElement.getSignature();
            let sigString = signatureElement.getSignatureString();

            $('input[name="img_signature_base64"]').val(sigImage);
        }

        const clearSignature = () => {
            signatureElement.clearCanvas();
            $('input[name="img_signature_base64"]').val('');
        }
    </script>
@endsection