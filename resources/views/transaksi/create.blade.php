@extends('adminlte::page')

@section('title', 'Tambah Transaksi')

@section('content_header')
    <h1>Tambah Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Transaksi</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="POST" id="form-transaksi">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" placeholder="Opsional">
                            @error('nama_pelanggan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h3 class="card-title">Detail Produk</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn-sm" id="tambah-produk">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabel-produk">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Panjang</th>
                                        <th>Lebar</th>
                                        <th>Qty (Otomatis)</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris produk akan ditambahkan di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-control-static">
                                <span class="badge badge-warning">Pending</span>
                                <small class="text-muted ml-2">Status transaksi baru otomatis pending</small>
                            </div>
                            <input type="hidden" name="status" value="pending">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="diskon">Diskon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon" value="{{ old('diskon', 0) }}" min="0">
                            </div>
                            @error('diskon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="total" name="total" value="{{ old('total', 0) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_bayar">Total Bayar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="total_bayar" name="total_bayar" value="{{ old('total_bayar', 0) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Template untuk baris produk baru -->
    <template id="template-row-produk">
        <tr class="baris-produk">
            <td>
                <select class="form-control produk-select" name="produk_id[]" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" data-harga="{{ $produk->harga_jual }}">
                            {{ $produk->nama }} - Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" class="form-control panjang-input" name="panjang[]" value="0" min="0" step="0.01" placeholder="Panjang" required>
            </td>
            <td>
                <input type="number" class="form-control lebar-input" name="lebar[]" value="0" min="0" step="0.01" placeholder="Lebar" required>
            </td>
            <td>
                <input type="number" class="form-control qty-input" name="qty[]" value="0" readonly>
            </td>
            <td>
                <input type="number" class="form-control harga-input" name="harga[]" value="0" readonly>
            </td>
            <td>
                <input type="number" class="form-control jumlah-input" name="jumlah[]" value="0" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm hapus-produk">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    </template>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Tambah baris produk
            $('#tambah-produk').click(function() {
                const template = document.querySelector('#template-row-produk');
                const clone = document.importNode(template.content, true);
                $('#tabel-produk tbody').append(clone);
                
                // Initialize select2 for new row if using select2
                // $('.produk-select').select2();
                
                hitungTotal();
            });
            
            // Hapus baris produk
            $(document).on('click', '.hapus-produk', function() {
                $(this).closest('tr').remove();
                hitungTotal();
            });
            
            // Update harga saat produk dipilih
            $(document).on('change', '.produk-select', function() {
                const row = $(this).closest('tr');
                const harga = $(this).find(':selected').data('harga') || 0;
                row.find('.harga-input').val(harga);
                updateJumlah(row);
            });
            
            // Update qty dan jumlah saat panjang atau lebar berubah
            $(document).on('input', '.panjang-input, .lebar-input', function() {
                const row = $(this).closest('tr');
                updateQty(row);
                updateJumlah(row);
            });
            
            // Update total saat diskon berubah
            $('#diskon').on('input', function() {
                hitungTotal();
            });
            
            // Fungsi untuk menghitung qty dari panjang x lebar
            function updateQty(row) {
                const panjang = parseFloat(row.find('.panjang-input').val()) || 0;
                const lebar = parseFloat(row.find('.lebar-input').val()) || 0;
                
                // Qty adalah hasil dari panjang x lebar
                const qty = panjang * lebar;
                row.find('.qty-input').val(qty.toFixed(2));
            }
            
            // Fungsi untuk menghitung jumlah per baris
            function updateJumlah(row) {
                const qty = parseFloat(row.find('.qty-input').val()) || 0;
                const harga = parseFloat(row.find('.harga-input').val()) || 0;
                
                // Jumlah adalah qty x harga
                const jumlah = qty * harga;
                row.find('.jumlah-input').val(jumlah);
                hitungTotal();
            }
            
            // Fungsi untuk menghitung total keseluruhan
            function hitungTotal() {
                let total = 0;
                $('.jumlah-input').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                
                const diskon = parseFloat($('#diskon').val()) || 0;
                const totalBayar = total - diskon;
                
                $('#total').val(total);
                $('#total_bayar').val(totalBayar);
            }
            
            // Tambahkan baris pertama saat halaman dimuat
            if ($('#tabel-produk tbody tr').length === 0) {
                $('#tambah-produk').click();
            }
        });
    </script>
@stop
