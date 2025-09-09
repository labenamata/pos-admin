@extends('adminlte::page')

@section('title', 'Daftar Transaksi')

@section('content_header')
    <h1>Daftar Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Data Transaksi</h3>
                <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <form action="{{ route('transaksi.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="tanggal_mulai" class="mr-2">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="form-group mr-2">
                            <label for="nama_pelanggan" class="mr-2">Pelanggan:</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}" placeholder="Nama Pelanggan">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 100px">Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Catatan</th>
                        <th style="width: 150px">Total</th>
                        <th style="width: 150px">Diskon</th>
                        <th style="width: 150px">Total Bayar</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 400px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $key => $transaksi)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $transaksi->nama_pelanggan ?? 'Umum' }}</td>
                            <td>
                                @if($transaksi->note)
                                    <span class="text-muted" title="{{ $transaksi->note }}">
                                        {{ Str::limit($transaksi->note, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                @if($transaksi->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaksi->status == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($transaksi->status == 'batal')
                                    <span class="badge badge-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('transaksi.pembayaran', $transaksi->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-money-bill-wave"></i> Pembayaran
                                </a>
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @role('admin')
                                <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "order": [[ 1, "desc" ]]
            });
            
            // Cek apakah ada struk yang perlu dicetak
            @if(session('print_struk'))
                // Buka struk di tab baru
                var strukUrl = "{{ session('print_struk') }}";
                window.open(strukUrl, '_blank');
            @endif
        });
    </script>
@stop
