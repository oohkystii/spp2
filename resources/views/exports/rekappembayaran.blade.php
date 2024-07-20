@php
if (!function_exists('ubahNamaBulan')) {
    function ubahNamaBulan($bulan)
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $bulanIndo[$bulan];
    }
}

if (!function_exists('bulanSPP')) {
    function bulanSPP()
    {
        return [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]; // July to June
    }
}
@endphp

<table>
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            @foreach ($header as $bulan)
                <th>{{ ubahNamaBulan($bulan) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataRekap as $rekap)
            <tr>
                <td>{{ $rekap['siswa']->nama }}</td>
                <td>{{ $rekap['siswa']->kelas }}</td>
                @foreach ($rekap['dataTagihan'] as $tagihan)
                    <td>{{ $tagihan['tanggal_lunas'] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
