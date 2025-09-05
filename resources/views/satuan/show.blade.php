@extends('adminlte::page')

@section('title', 'Detail Satuan')

@section('content_header')
    <h1>Detail Satuan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Satuan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">ID</th>
                    <td>{{ $satuan->id }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $satuan->nama }}</td>
                </tr>
                <tr>
                    <th>Dibuat pada</th>
                    <td>{{ $satuan->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Diperbarui pada</th>
                    <td>{{ $satuan->updated_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            </table>
            
            <div class="mt-3">
                <a href="{{ route('satuan.edit', $satuan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('satuan.index') }}" class="btn btn-secondary">
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
