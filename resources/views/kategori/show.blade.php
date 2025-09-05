@extends('adminlte::page')

@section('title', 'Detail Kategori')

@section('content_header')
    <h1>Detail Kategori</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Kategori</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">ID</th>
                    <td>{{ $kategori->id }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $kategori->nama }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $kategori->deskripsi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Dibuat pada</th>
                    <td>{{ $kategori->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Diperbarui pada</th>
                    <td>{{ $kategori->updated_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            </table>
            
            <div class="mt-3">
                <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
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
