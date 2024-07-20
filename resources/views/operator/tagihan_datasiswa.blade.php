<div class="card">
    <h5 class="card-header">DATA TAGIHAN SPP SISWA {{ strtoupper($periode) }}</h5>
    <div class="card-body">
        <table class="table table-sm">
            <tr>
                <td rowspan="8" width="100">
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
                <td>Kelas</td>
                <td>: {{ $siswa->kelas }}</td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>: {{ $siswa->jurusan }}</td>
            </tr>
        </table>
    </div>
</div>
