@extends('adminlte::page')

@section('title', 'Laporan Transaksi')

@section('content_header')
    <h1>Laporan Transaksi</h1>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.transaksi') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="tanggal_mulai" class="mr-2">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggal_mulai }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="status" class="mr-2">Status:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="" {{ $status == '' ? 'selected' : '' }}>Semua</option>
                                <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('laporan.cetak') }}?tanggal_mulai={{ $tanggal_mulai }}&tanggal_akhir={{ $tanggal_akhir }}&status={{ $status }}" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPerStatus['selesai'], 0, ',', '.') }}</h3>
                    <p>Total Transaksi Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahPerStatus['selesai'] }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPerStatus['pending'], 0, ',', '.') }}</h3>
                    <p>Total Transaksi Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahPerStatus['pending'] }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPerStatus['batal'], 0, ',', '.') }}</h3>
                    <p>Total Transaksi Batal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="small-box-footer">{{ $jumlahPerStatus['batal'] }} transaksi</div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{ number_format($totalNilaiTransaksi, 0, ',', '.') }}</h3>
                    <p>Total Semua Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="small-box-footer">{{ $totalTransaksi }} transaksi</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Transaksi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-transaksi">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Total Item</th>
                                    <th>Diskon</th>
                                    <th>Total Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $key => $transaksi)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $transaksi->kode }}</td>
                                        <td>{{ $transaksi->nama_pelanggan }}</td>
                                        <td>
                                            @if($transaksi->status == 'selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @elseif($transaksi->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Batal</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaksi->detailTransaksi->sum('qty') }}</td>
                                        <td>Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('laporan.detail', $transaksi->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data transaksi</td>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabel-transaksi').DataTable({
                "order": [[ 1, "desc" ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@stop
