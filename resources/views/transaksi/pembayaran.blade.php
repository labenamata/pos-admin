@extends('adminlte::page')

@section('title', 'Pembayaran Transaksi')

@section('content_header')
    <h1>Pembayaran Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pembayaran Transaksi #{{ $transaksi->id }}</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h3 class="card-title">Form Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi.proses-pembayaran', $transaksi->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select class="form-control @error('metode_pembayaran') is-invalid @enderror" id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="">-- Pilih Metode Pembayaran --</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Status transaksi akan otomatis diubah menjadi "selesai" setelah pembayaran diproses.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catatan">Catatan Pembayaran</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Catatan pembayaran (opsional)">{{ old('catatan') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="cetak_struk" name="cetak_struk" value="0">
                                        <label for="cetak_struk" class="custom-control-label">Cetak struk setelah pembayaran diproses</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                            <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-secondary">Kembali</a>
                            @if($transaksi->status == 'selesai')
                                <a href="{{ route('transaksi.cetak-struk', $transaksi->id) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-print"></i> Cetak Struk
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
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
