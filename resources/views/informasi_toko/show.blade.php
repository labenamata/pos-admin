@extends('adminlte::page')

@section('title', 'Detail Informasi Toko')

@section('content_header')
    <h1>Detail Informasi Toko</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Toko</h3>
                    <div class="card-tools">
                        <a href="{{ route('informasi-toko.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Toko:</label>
                                <p class="form-control-static">{{ $informasiToko->nama_toko }}</p>
                            </div>
                            <div class="form-group">
                                <label>Alamat:</label>
                                <p class="form-control-static">{{ $informasiToko->alamat }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon:</label>
                                <p class="form-control-static">{{ $informasiToko->no_telepon }}</p>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <p class="form-control-static">{{ $informasiToko->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($informasiToko->logo)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Logo:</label>
                                    <div>
                                        <img src="{{ asset('storage/logo/' . $informasiToko->logo) }}" alt="Logo Toko" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('informasi-toko.edit', $informasiToko->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('informasi-toko.destroy', $informasiToko->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi toko ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
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
            // Script tambahan jika diperlukan
        });
    </script>
@stop
