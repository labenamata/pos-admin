@extends('adminlte::page')

@section('title', 'Detail Transaksi')

@section('content_header')
    <h1>Detail Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Transaksi</h3>
            <div class="card-tools">
                <a href="{{ route('transaksi.pembayaran', $transaksi->id) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-money-bill-wave"></i> Pembayaran
                </a>
                <a href="{{ route('transaksi.invoice', $transaksi->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-invoice"></i> Invoice
                </a>
                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">ID Transaksi</th>
                            <td>{{ $transaksi->id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <td>{{ $transaksi->nama_pelanggan ?? 'Umum' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($transaksi->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaksi->status == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($transaksi->status == 'batal')
                                    <span class="badge badge-danger">Batal</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">Total</th>
                            <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Diskon</th>
                            <td>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat pada</th>
                            <td>{{ $transaksi->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h3 class="card-title">Detail Produk</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Produk</th>
                                    <th>Panjang</th>
                                    <th>Lebar</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi->detailTransaksi as $key => $detail)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $detail->produk->nama }}</td>
                                        <td>{{ $detail->panjang > 0 ? $detail->panjang : '-' }}</td>
                                        <td>{{ $detail->lebar > 0 ? $detail->lebar : '-' }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>{{ $detail->produk->satuan->nama }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data detail transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right">Total:</th>
                                    <th>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">Diskon:</th>
                                    <th>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">Total Bayar:</th>
                                    <th>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                @role('admin')
                <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Semua detail transaksi juga akan dihapus.')">
                        <i class="fas fa-trash"></i> Hapus Transaksi
                    </button>
                </form>
                @endrole
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Script tambahan jika diperlukan
        });
    </script>
@stop
