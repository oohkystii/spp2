<table class="table table-bordered">
    <thead class="table-dark">
        <tr style="height: 50px;">
            <th width="1%" style="text-align: center">No</th>
            <th style="text-align: start">Bulan</th>
            <th style="text-align: center">Jumlah</th>
            <th style="text-align: center">Tanggal</th>
            <th>Paraf</th>
        </tr>
    </thead>
    @foreach ($kartuSpp as $item)
    <tr class="item">
        <td style="text-align: center">{{ $loop->iteration }}</td>
        <td style="text-align: start">{{ $item['bulan'] . ' ' . $item['tahun'] }}</td>
        <td style="text-align: end">{{ formatRupiah($item['total_tagihan']) }}</td>
        <td style="text-align: end">{{ $item['tanggal_bayar'] }}</td>
        <td>&nbsp;</td>
    </tr>
    @endforeach
</table>
<a href="{{ route('kartuspp.index', [
    'siswa_id' => $siswa->id,
    'tahun' => request('tahun'),
]) }}" 
    class="mt-3" target="blank"><i class="fa fa-print"></i> Cetak Kartu SPP {{ request('tahun') }}
</a>