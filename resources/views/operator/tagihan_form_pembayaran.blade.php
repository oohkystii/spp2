{!! Form::model($model, ['route' => 'pembayaran.store', 'method' => 'POST']) !!}
{!! Form::hidden('tagihan_id', $tagihan->id, []) !!}
<div class="form-group">
    <label for="tanggal_bayar">Tanggal Pembayaran</label>
    {!! Form::date('tanggal_bayar', $model->tanggal_bayar ?? \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
    <span class="text-danger">{{ $errors->first('tanggal_bayar') }}</span>
</div>
<div class="form-group mt-3">
    <label for="jumlah_dibayar">Jumlah Yang Dibayarkan</label>
    {!! Form::text('jumlah_dibayar', $tagihan->total_tagihan, ['class' => 'form-control rupiah']) !!}
    <span class="text-danger">{{ $errors->first('jumlah_dibayar') }}</span>
</div>
{!! Form::submit('SIMPAN', ['class' => 'btn btn-primary mt-3']) !!}
{!! Form::close() !!}
