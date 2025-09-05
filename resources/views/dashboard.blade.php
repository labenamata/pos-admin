@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="tanggal_mulai" class="mr-2">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggal_mulai }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPenjualanSelesai, 0, ',', '.') }}</h3>
                    <p>Total Penjualan Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahTransaksiSelesai }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPenjualanPending, 0, ',', '.') }}</h3>
                    <p>Total Penjualan Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahTransaksiPending }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPenjualanSelesai + $totalPenjualanPending, 0, ',', '.') }}</h3>
                    <p>Total Semua Penjualan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahTransaksiSelesai + $jumlahTransaksiPending }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $penjualanPerItem->count() }}</h3>
                    <p>Produk Terjual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="small-box-footer">Periode {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penjualan Per Item</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualanPerItem as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->total_qty }}</td>
                                        <td>Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data penjualan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
            $('table').DataTable({
                "order": [[ 3, "desc" ]]
            });
        });
    </script>
@stop
