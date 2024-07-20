<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>TANGGAL</th>
            <th>METODE</th>
            <th class="text-end">JUMLAH</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tagihan->pembayaran as $item)
        <tr>
            <td>{{ $item->tanggal_bayar->translatedFormat('d/m/Y') }}</td>
            <td>{{ $item->metode_pembayaran }}</td>
            <td class="text-end">{{ formatRupiah($item->jumlah_dibayar) }}</td>
            <td>
                {!! Form::open([
                'route' => ['pembayaran.destroy', $item->id],
                'method' => 'DELETE',
                'onsubmit' => 'return confirm("Yakin ingin menghapus data ini")',
                ]) !!}
                <button type="submit" class="btn m-0 p-0 mx-2">
                    <i class="fa fa-trash"></i>
                </button>
                <a href="{{ route('kwitansipembayaran.show', $item->id) }}" target="_blank"><i class="fa fa-print"></i></a>
                {!! Form::close() !!}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">Data Belum ada</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total Pembayaran</td>
            <td class="text-end">{{ formatRupiah($tagihan->total_pembayaran) }}</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
<h6 class="mt-3">Status Pembayaran : {{ strtoupper($tagihan->status) }}</h6>