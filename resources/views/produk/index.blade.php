@extends('adminlte::page')

@section('title', 'Daftar Produk')

@section('content_header')
    <h1>Daftar Produk</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Data Produk</h3>
                <a href="{{ route('produk.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Pokok</th>
                        <th>Harga Jual</th>
                        <th style="width: 300px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $key => $produk)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $produk->nama }}</td>
                            <td>{{ $produk->kategori->nama }}</td>
                            <td>{{ $produk->satuan->nama }}</td>
                            <td>Rp {{ number_format($produk->harga_pokok ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('produk.show', $produk->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('table').DataTable();
        });
    </script>
@stop
