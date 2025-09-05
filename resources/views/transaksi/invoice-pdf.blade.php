<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaksi->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .invoice-info {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .invoice-col {
            width: 30%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-table {
            width: 40%;
            margin-left: auto;
        }
        .payment-method {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
            display: inline-block;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .badge-primary {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $toko->nama_toko ?? 'POS Admin' }}</h2>
            <p>{{ $toko->alamat ?? 'Alamat Toko' }}</p>
            <p>Telepon: {{ $toko->no_telepon ?? '-' }} | Email: {{ $toko->email ?? '-' }}</p>
        </div>

        <h3>INVOICE #{{ $transaksi->id }}</h3>
        
        <div class="invoice-info">
            <div class="invoice-col">
                <strong>Dari:</strong><br>
                {{ $toko->nama_toko ?? 'POS Admin' }}<br>
                {{ $toko->alamat ?? 'Alamat Toko' }}<br>
                Telepon: {{ $toko->no_telepon ?? '-' }}<br>
                Email: {{ $toko->email ?? '-' }}
            </div>
            
            <div class="invoice-col">
                <strong>Kepada:</strong><br>
                {{ $transaksi->nama_pelanggan ?? 'Umum' }}<br>
            </div>
            
            <div class="invoice-col">
                <strong>Detail Invoice:</strong><br>
                <b>Tanggal:</b> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}<br>
                <b>Status:</b> 
                @if($transaksi->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif($transaksi->status == 'selesai')
                    <span class="badge badge-success">Selesai</span>
                @elseif($transaksi->status == 'batal')
                    <span class="badge badge-danger">Batal</span>
                @endif
                <br>
                <b>Metode Pembayaran:</b>
                @if($transaksi->metode_pembayaran == 'tunai')
                    <span class="badge badge-info">Tunai</span>
                @elseif($transaksi->metode_pembayaran == 'transfer')
                    <span class="badge badge-primary">Transfer</span>
                @else
                    -
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detailTransaksi as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail->produk->nama }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>{{ $detail->produk->satuan->nama }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between;">
            <div class="payment-method">
                <p><strong>Metode Pembayaran:</strong></p>
                <p>
                    @if($transaksi->metode_pembayaran == 'tunai')
                        Tunai
                    @elseif($transaksi->metode_pembayaran == 'transfer')
                        Transfer
                    @else
                        Tunai/Transfer
                    @endif
                </p>
            </div>
            
            <table class="total-table">
                <tr>
                    <th>Subtotal:</th>
                    <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Diskon:</th>
                    <td>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td><strong>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda berbelanja di {{ $toko->nama_toko ?? 'POS Admin' }}.</p>
            <p>Invoice ini dihasilkan secara otomatis dan sah tanpa tanda tangan.</p>
        </div>
    </div>
</body>
</html>
