<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 80mm; /* Lebar standar struk kasir */
        }
        .container {
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .header h2, .header p {
            margin: 2px 0;
        }
        .info {
            margin-bottom: 10px;
            font-size: 9px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th, table td {
            text-align: left;
            padding: 2px;
            font-size: 9px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .total {
            text-align: right;
            margin-bottom: 10px;
        }
        .total p {
            margin: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 8px;
        }
        .bold {
            font-weight: bold;
        }
        @media print {
            @page {
                margin: 0;
                size: 80mm auto; /* Lebar 80mm, tinggi menyesuaikan */
            }
            body {
                margin: 0;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $toko->nama_toko ?? 'POS Admin' }}</h2>
            <p>{{ $toko->alamat ?? 'Alamat Toko' }}</p>
            <p>Telp: {{ $toko->no_telepon ?? '-' }}</p>
        </div>

        <div class="info">
            <p>No: #{{ $transaksi->id }}</p>
            <p>Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ auth()->user()->name ?? 'Admin' }}</p>
            <p>Pelanggan: {{ $transaksi->nama_pelanggan ?? 'Umum' }}</p>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detailTransaksi as $detail)
                <tr>
                    <td>{{ $detail->produk->nama }}</td>
                    <td>{{ $detail->qty }} {{ $detail->produk->satuan->nama }}</td>
                    <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total">
            <p>Subtotal: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
            <p>Diskon: Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</p>
            <p class="bold">Total: Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
            <p>Metode Pembayaran: 
                @if($transaksi->metode_pembayaran == 'tunai')
                    Tunai
                @elseif($transaksi->metode_pembayaran == 'transfer')
                    Transfer
                @else
                    -
                @endif
            </p>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Terima kasih telah berbelanja</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</body>
</html>
