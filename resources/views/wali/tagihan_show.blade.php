@extends('layouts.app_sneat_wali')

@section('js')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        $(document).ready(function () {
            $("#pay-button").click(function (e) {
                e.preventDefault();
                
                var metodePembayaran = $('input[name="metode_pembayaran"]:checked').val();
                var url = `/walimurid/pembayaranmidtrans?tagihan_id={{ $tagihan->id }}&metode_pembayaran=${metodePembayaran}`;
                
                $.getJSON(url, function(data) {
                    snap.pay(data.snapToken, {
                        onSuccess: function(result) {
                            window.location.href = window.location.href + "?check=true";
                        },
                        onPending: function(result) {
                            window.location.href = window.location.href + "?check=true";
                        },
                        onError: function(result) {
                            window.location.href = window.location.href + "?check=true";
                        },
                    });
                });
            });
        });
    </script>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">TAGIHAN SPP {{ strtoupper($siswa->nama) }}</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td rowspan="8" width="100" class="align-top">
                                        <img src="{{ \Storage::url($siswa->foto) }}" alt="{{ $siswa->nama }}" width="100">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50">NISN</td>
                                    <td>: {{ $siswa->nisn }}</td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>: {{ $siswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td>: {{ $siswa->jurusan }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>: {{ $siswa->kelas }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <table>
                                <tr>
                                    <td>No. Tagihan</td>
                                    <td>: {{ $tagihan->getNomorTagihan() }}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Tagihan</td>
                                    <td>: {{ $tagihan->tanggal_tagihan->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Akhir Pembayaran</td>
                                    <td>: {{ $tagihan->tanggal_jatuh_tempo->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Status Pembayaran</td>
                                    <td>: {{ $tagihan->getStatusTagihanWali() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right">
                                        <a href="{{ route('invoice.show', $tagihan->id) }}" target="_blank"><i class="fa fa-file-pdf"></i> Cetak Invoice tagihan</a>
                                    </td>
                                </tr>                                
                            </table>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <td width="1%">No</td>
                                <td>Nama Tagihan</td>
                                <td class="text-end">Jumlah Tagihan</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihan->tagihanDetails as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_biaya }}</td>
                                    <td class="text-end">{{ formatRupiah($item->jumlah_biaya) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold">{{ formatRupiah($tagihan->tagihanDetails->sum('jumlah_biaya')) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="alert alert-secondary mt-4" role="alert">
                        <h5>Pembayaran Otomatis</h5>
                        <p>Pembayaran otomatis menggunakan pihak ketiga, anda akan dikenakan biaya tambahan sebesar Rp. 4000,-</p>
                        <p>Status Pembayaran: <b>{{ getStatusPembayaranTeks($statusPembayaran) }}</b></p>
                        
                        <!-- Note about installment payments -->
                        <div class="alert alert-info">
                            <strong>Catatan:</strong> Pembayaran angsuran hanya dapat dilakukan maksimal 2 kali.
                        </div>

                        <!-- Pilihan Pembayaran -->
                        @if ($tagihan->status !== 'angsur' && $tagihan->status !== 'lunas')
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="bayarPenuh" value="full" checked>
                                <label class="form-check-label" for="bayarPenuh">
                                    Bayar Penuh
                                </label>
                            </div>
                        @endif
                        @if ($tagihan->status !== 'lunas')
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="angsuran" value="installment">
                                <label class="form-check-label" for="angsuran">
                                    Cicilan (2x Pembayaran)
                                </label>
                            </div>
                        @endif
                        <button id="pay-button" class="btn btn-primary mt-3">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
