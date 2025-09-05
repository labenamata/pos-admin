@extends('adminlte::page')

@section('title', 'Detail Produk')

@section('content_header')
    <h1>Detail Produk</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Produk</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">ID</th>
                    <td>{{ $produk->id }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $produk->nama }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $produk->kategori->nama }}</td>
                </tr>
                <tr>
                    <th>Satuan</th>
                    <td>{{ $produk->satuan->nama }}</td>
                </tr>
                <tr>
                    <th>Harga Pokok</th>
                    <td>Rp {{ number_format($produk->harga_pokok ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Harga Jual</th>
                    <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dibuat pada</th>
                    <td>{{ $produk->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Diperbarui pada</th>
                    <td>{{ $produk->updated_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            </table>
            
            <div class="mt-3">
                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
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
