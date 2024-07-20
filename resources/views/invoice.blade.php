<!DOCTYPE html>
<html>
<head>
    <title>
        {{ @$title != '' ? "$title |" : '' }} {{ settings()->get('app_name', 'SPP') }}
    </title>
    <meta charset="utf-8" />

    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
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
            margin-right: 10px;
        }

        .print-btn {
            background-color: #007bff;
        }

        .download-pdf-btn:hover, .print-btn:hover {
            background-color: #0056b3;
        }

        /* Additional styles for printing */
        @media print {
            body {
                background-color: #fff;
            }

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
    <table>
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
                        <td>
                            Nama Siswa: {{ $tagihan->siswa->nama }} ({{ $tagihan->siswa->nisn }})<br />
                            Kelas: {{ $tagihan->siswa->kelas }}<br />
                            Jurusan: {{ $tagihan->siswa->jurusan }}
                        </td>

                        <td>
                            Invoice #: {{ $tagihan->id }}<br />
                            Tanggal Tagihan: {{ $tagihan->tanggal_tagihan->translatedFormat(' d F Y ') }} <br />
                            Tanggal Jatuh Tempo: {{ $tagihan->tanggal_jatuh_tempo->translatedFormat(' d F Y ') }} <br />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td width="1%">No</td>
            <td style="text-align: start">Item Tagihan</td>
            <td style="text-align: end">Sub Total</td>
        </tr>
        @foreach ($tagihan->tagihanDetails as $item)
            <tr class="item">
                <td>{{ $loop->iteration }}</td>
                <td style="text-align: start">{{ $item->nama_biaya }}</td>
                <td style="text-align: end">{{ formatRupiah($item->jumlah_biaya) }}</td>
            </tr>
        @endforeach

        <tr class="total" style="background: #eee">
            <td colspan="2" style="text-align: center;font-weight: bold">TOTAL</td>
            <td>{{ formatRupiah($tagihan->total_tagihan) }}</td>
        </tr>
        <tr>
            <td colspan="3">
                <div>
                    Terbilang: <i>{{ ucwords(terbilang($tagihan->total_tagihan)) }}</i>
                </div>
                <hr>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Leles, {{ $tagihan->tanggal_tagihan->translatedFormat('d F Y') }} <br />
                Mengetahui, <br />
                @include('informasi_pj')
            </td>
        </tr>
    </table>
    <br />
    <a class="download-pdf-btn" href="{{ url()->full() . '&output=pdf' }}" target="_blank">Download PDF</a>
    <a class="print-btn" href="#" onclick="window.print()">Cetak</a>
</div>
</body>
</html>
