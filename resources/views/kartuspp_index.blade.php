<!DOCTYPE html>
<html>
<head>
    <title>
        {{ @$title != '' ? "$title |" : '' }} {{ settings()->get('app_name', 'SPP') }}
    </title>
    <meta charset="utf-8" />
    <style>
        /* Gaya utama */
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        /* Gaya tabel */
        .table-tagihan {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .table-tagihan th, .table-tagihan td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .table-tagihan tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-tagihan th {
            background-color: #eee;
        }

        /* Tombol Download PDF dan Cetak */
        .download-pdf-btn, .print-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .print-btn {
            background-color: #007bff;
        }

        .download-pdf-btn:hover, .print-btn:hover {
            background-color: #0056b3;
        }
        /* Additional styles for printing */
        @media print {
            .invoice-box {
                max-width: 100%;
                margin: 0;
                border: none;
                box-shadow: none;
                padding: 10px;
            }

            .download-pdf-btn, .print-btn {
                display: none; /* Hide the download and print buttons when printing */
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr>
                @include('header_invoice_kartu')
            </tr>
            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>
            <tr class="information">
                <td colspan="3">
                    <table>
                        <tr>
                            <td style="padding-bottom: 5px;">
                                <b>Tahun Ajaran {{ getTahunAjaranFull() }} <br /></b>
                                Nama Siswa : {{ $siswa->nama }} ({{ $siswa->nisn }})<br />
                                Kelas : {{ $siswa->kelas }} <br />
                                Jurusan : {{ $siswa->jurusan }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table-tagihan">
                        <tr class="heading">
                            <th width="5%">No</th>
                            <th style="text-align: start">Bulan</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Paraf</th>
                            <th>Keterangan</th>
                        </tr>
                        @foreach ($kartuSpp as $item)
                        <tr class="item">
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align: start">{{ $item['bulan'] . ' ' . $item['tahun'] }}</td>
                            <td>{{ formatRupiah($item['total_tagihan']) }}</td>
                            <td>{{ $item['tanggal_bayar'] }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <br />
                    Leles, {{ now()->translatedFormat('d F Y') }} <br />
					Mengetahui, <br />
					@include('informasi_pj')
				</td>
                </td>
            </tr>
        </table>
        <br />
        <a class="download-pdf-btn" href="{{ url()->full() . '&output=pdf' }}" target="_blank">Download PDF</a>&nbsp;&nbsp;
        <a class="print-btn" href="#" onclick="window.print()">Cetak</a>
    </div>
</body>
</html>
