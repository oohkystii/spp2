<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Tagihan</th>
            <th>Jumlah Tagihan</th>
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
            <td colspan="2">Total Tagihan</td>
            <td class="text-end">{{ formatRupiah($tagihan->total_tagihan) }}</td>
        </tr>
    </tfoot>
</table>
<a href="{{ route('invoice.show', $tagihan->id) }}" target="_blank">
    <i class="fa fa-file-pdf"></i> Download Invoice
</a>