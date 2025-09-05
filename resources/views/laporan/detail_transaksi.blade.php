@extends('adminlte::page')

@section('title', 'Detail Transaksi')

@section('content_header')
    <h1>Detail Transaksi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Transaksi</h3>
                    <div class="card-tools">
                        <a href="{{ route('laporan.transaksi') }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="javascript:window.print()" class="btn btn-sm btn-success">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                        <a href="{{ route('transaksi.cetak-struk', $transaksi->id) }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-receipt"></i> Cetak Struk
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Kode Transaksi</th>
                                    <td>: {{ $transaksi->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Pelanggan</th>
                                    <td>: {{ $transaksi->nama_pelanggan }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Status</th>
                                    <td>: 
                                        @if($transaksi->status == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($transaksi->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Batal</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diskon</th>
                                    <td>: Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Bayar</th>
                                    <td>: Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>: 
                                        @if($transaksi->metode_pembayaran == 'tunai')
                                            <span class="badge badge-info">Tunai</span>
                                        @elseif($transaksi->metode_pembayaran == 'transfer')
                                            <span class="badge badge-primary">Transfer</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Item</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Panjang</th>
                                    <th>Lebar</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @forelse($transaksi->detailTransaksi as $key => $detail)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $detail->produk->nama }}</td>
                                        <td>{{ $detail->produk->kategori->nama }}</td>
                                        <td>{{ $detail->produk->satuan->nama }}</td>
                                        <td>{{ $detail->panjang ?? '-' }}</td>
                                        <td>{{ $detail->lebar ?? '-' }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                    @php $total += $detail->jumlah; @endphp
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada detail transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-right">Total</th>
                                    <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-right">Diskon</th>
                                    <th>Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-right">Total Bayar</th>
                                    <th>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
            .main-header, .main-sidebar, .main-footer {
                display: none !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
                padding-top: 0 !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            .card-header {
                background-color: #f8f9fa !important;
                color: #000 !important;
            }
        }
    </style>
@stop
