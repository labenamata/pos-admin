<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            width: 48%;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .kategori-section {
            margin-bottom: 20px;
        }
        .kategori-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        @media print {
            body {
                padding: 0;
                margin: 10px;
            }
            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN PRODUK</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        <p>Kategori: {{ $nama_kategori }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Produk Terjual</h3>
            <p>{{ number_format($totalQty, 0, ',', '.') }} item</p>
        </div>
        <div class="summary-box">
            <h3>Total Nilai Penjualan</h3>
            <p>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="kategori-section">
        <div class="kategori-title">Penjualan Per Kategori</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px">#</th>
                    <th>Kategori</th>
                    <th>Jumlah Item Terjual</th>
                    <th>Total Penjualan</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanPerKategori as $kategori => $data)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $kategori }}</td>
                        <td class="text-center">{{ number_format($data['total_qty'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $persentase = $totalPenjualan > 0 ? ($data['total_penjualan'] / $totalPenjualan) * 100 : 0;
                            @endphp
                            {{ number_format($persentase, 1) }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data penjualan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="kategori-title">Detail Penjualan Produk</div>
    <table>
        <thead>
            <tr>
                <th style="width: 30px">#</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Jumlah Terjual</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualanProduk as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td>{{ $item->nama_satuan }}</td>
                    <td class="text-center">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data penjualan produk</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total</th>
                <th class="text-center">{{ number_format($totalQty, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>

    <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-top: 20px;">
        Cetak Laporan
    </button>
</body>
</html>
