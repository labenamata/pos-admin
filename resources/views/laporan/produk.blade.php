@extends('adminlte::page')

@section('title', 'Laporan Penjualan Produk')

@section('content_header')
    <h1>Laporan Penjualan Produk</h1>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.produk') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="tanggal_mulai" class="mr-2">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggal_mulai }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="kategori_id" class="mr-2">Kategori:</label>
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('laporan.cetak.produk') }}?tanggal_mulai={{ $tanggal_mulai }}&tanggal_akhir={{ $tanggal_akhir }}&kategori_id={{ $kategori_id }}" 
                           class="btn btn-success mr-2" target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                        <a href="{{ route('laporan.produk.export') }}?tanggal_mulai={{ $tanggal_mulai }}&tanggal_akhir={{ $tanggal_akhir }}&kategori_id={{ $kategori_id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-shopping-basket"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Produk Terjual</span>
                                    <span class="info-box-number">{{ number_format($totalQty, 0, ',', '.') }} item</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Nilai Penjualan</span>
                                    <span class="info-box-number">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Periode</h3>
                </div>
                <div class="card-body">
                    <p><strong>Dari:</strong> {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }}</p>
                    <p><strong>Sampai:</strong> {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
                    <p><strong>Kategori:</strong> {{ $kategori_id ? $kategoris->where('id', $kategori_id)->first()->nama : 'Semua Kategori' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penjualan Per Kategori</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Kategori</th>
                                    <th>Jumlah Item Terjual</th>
                                    <th>Total Penjualan</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualanPerKategori as $kategori => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori }}</td>
                                        <td>{{ number_format($data['total_qty'], 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $persentase = $totalPenjualan > 0 ? ($data['total_penjualan'] / $totalPenjualan) * 100 : 0;
                                            @endphp
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persentase }}%" 
                                                     aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($persentase, 1) }}%
                                                </div>
                                            </div>
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Penjualan Produk</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-produk">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
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
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->nama_kategori }}</td>
                                        <td>{{ $item->nama_satuan }}</td>
                                        <td>{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
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
                                    <th>{{ number_format($totalQty, 0, ',', '.') }}</th>
                                    <th>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</th>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabel-produk').DataTable({
                "order": [[ 5, "desc" ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
