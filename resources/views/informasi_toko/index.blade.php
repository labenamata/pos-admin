@extends('adminlte::page')

@section('title', 'Informasi Toko')

@section('content_header')
    <h1>Informasi Toko</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Informasi Toko</h3>
                    <div class="card-tools">
                        @if(!$informasiToko)
                            <a href="{{ route('informasi-toko.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Informasi Toko
                            </a>
                        @endif
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

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($informasiToko)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-store"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nama Toko</span>
                                        <span class="info-box-number">{{ $informasiToko->nama_toko }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-phone"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">No. Telepon</span>
                                        <span class="info-box-number">{{ $informasiToko->no_telepon }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-map-marker-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Alamat</span>
                                        <span class="info-box-number">{{ $informasiToko->alamat }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-envelope"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Email</span>
                                        <span class="info-box-number">{{ $informasiToko->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($informasiToko->logo)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Logo Toko</h3>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset('storage/logo/' . $informasiToko->logo) }}" alt="Logo Toko" class="img-fluid" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('informasi-toko.edit', $informasiToko->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Informasi Toko
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informasi!</h5>
                            Belum ada informasi toko yang tersimpan. Silahkan tambahkan informasi toko terlebih dahulu.
                        </div>
                    @endif
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
