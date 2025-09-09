@extends('adminlte::page')

@section('title', 'Invoice Transaksi')

@section('content_header')
    <h1>Invoice Transaksi</h1>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice #{{ $transaksi->id }}</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <a href="{{ route('transaksi.export-pdf', $transaksi->id) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('transaksi.cetak-struk', $transaksi->id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-receipt"></i> Cetak Struk
                        </a>
                        <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-store"></i> {{ $toko->nama_toko ?? 'POS Admin' }}
                                    <small class="float-right">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</small>
                                </h4>
                            </div>
                        </div>

                        <!-- info row -->
                        <div class="row invoice-info mt-3">
                            <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>{{ $toko->nama_toko ?? 'POS Admin' }}</strong><br>
                                    {{ $toko->alamat ?? 'Alamat Toko' }}<br>
                                    Telepon: {{ $toko->no_telepon ?? '-' }}<br>
                                    Email: {{ $toko->email ?? '-' }}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Pelanggan</strong><br>
                                    {{ $transaksi->nama_pelanggan ?? 'Umum' }}<br>
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #{{ $transaksi->id }}</b><br>
                                <b>Tanggal:</b> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}<br>
                                <b>Status:</b> 
                                @if($transaksi->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaksi->status == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($transaksi->status == 'batal')
                                    <span class="badge badge-danger">Batal</span>
                                @endif
                                @if($transaksi->note)
                                <br><b>Catatan:</b> {{ $transaksi->note }}
                                @endif
                            </div>
                        </div>

                        <!-- Table row -->
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Produk</th>
                                            <th>Panjang</th>
                                            <th>Lebar</th>
                                            <th>Luas (Satuan)</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->detailTransaksi as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $detail->produk->nama }}</td>
                                            <td>{{ $detail->panjang > 0 ? $detail->panjang : '-' }}</td>
                                            <td>{{ $detail->lebar > 0 ? $detail->lebar : '-' }}</td>
                                            <td>{{ $detail->luas > 0 ? $detail->luas . ' (' . $detail->produk->satuan->nama . ')' : '-' }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p class="lead">Metode Pembayaran:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    Tunai/Transfer
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Diskon:</th>
                                            <td>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row no-print mt-4">
                            <div class="col-12">
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                <a href="{{ route('transaksi.export-pdf', $transaksi->id) }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>
                                <a href="{{ route('transaksi.cetak-struk', $transaksi->id) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-receipt"></i> Cetak Struk
                                </a>
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    @media print {
        .no-print, .main-header, .main-sidebar, .main-footer, .card-header, .card-tools {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .invoice {
            padding: 0 !important;
            border: 0 !important;
        }
        .card {
            border: 0 !important;
        }
        .card-body {
            padding: 0 !important;
        }
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Script tambahan jika diperlukan
    });
</script>
@stop
