<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
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
            width: 23%;
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
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-danger {
            background-color: #dc3545;
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
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        <p>Status: {{ $status ? ucfirst($status) : 'Semua' }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Transaksi Selesai</h3>
            <p>Jumlah: {{ $jumlahPerStatus['selesai'] }}</p>
            <p>Total: Rp {{ number_format($totalPerStatus['selesai'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Transaksi Pending</h3>
            <p>Jumlah: {{ $jumlahPerStatus['pending'] }}</p>
            <p>Total: Rp {{ number_format($totalPerStatus['pending'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Transaksi Batal</h3>
            <p>Jumlah: {{ $jumlahPerStatus['batal'] }}</p>
            <p>Total: Rp {{ number_format($totalPerStatus['batal'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Keseluruhan</h3>
            <p>Jumlah: {{ $totalTransaksi }}</p>
            <p>Total: Rp {{ number_format($totalNilaiTransaksi, 0, ',', '.') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">#</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Status</th>
                <th>Total Item</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $key => $transaksi)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $transaksi->kode }}</td>
                    <td>{{ $transaksi->nama_pelanggan }}</td>
                    <td class="text-center">
                        @if($transaksi->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($transaksi->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Batal</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $transaksi->detailTransaksi->sum('qty') }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total</th>
                <th class="text-right">Rp {{ number_format($transaksis->sum('diskon'), 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalNilaiTransaksi, 0, ',', '.') }}</th>
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
