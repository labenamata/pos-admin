@extends('adminlte::page')

@section('title', 'Edit Transaksi')

@section('content_header')
    <h1>Edit Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Transaksi</h3>
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

            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="form-transaksi">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal) }}" required>
                            @error('tanggal')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}" placeholder="Opsional">
                            @error('nama_pelanggan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3" placeholder="Catatan tambahan untuk transaksi ini (opsional)">{{ old('note', $transaksi->note) }}</textarea>
                            @error('note')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h3 class="card-title">Detail Produk</h3>
                        <div class="card-tools">
                            @if($transaksi->status == 'pending')
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambahProduk">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </button>
                            @else
                                <span class="badge badge-info">Produk hanya dapat ditambahkan jika status masih pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th>Panjang</th>
                                        <th>Lebar</th>
                                        <th>Luas</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->detailTransaksi as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $detail->produk->nama }}</td>
                                            <td>{{ $detail->panjang > 0 ? $detail->panjang : '-' }}</td>
                                            <td>{{ $detail->lebar > 0 ? $detail->lebar : '-' }}</td>
                                            <td>{{ $detail->luas > 0 ? $detail->luas : '-' }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <div class="input-group">
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $transaksi->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="selesai" {{ old('status', $transaksi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="batal" {{ old('status', $transaksi->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                                <div class="input-group-append">
                                    <a href="{{ route('transaksi.pembayaran', $transaksi->id) }}" class="btn btn-success">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                </div>
                            </div>
                            <small class="text-muted">Sebaiknya gunakan halaman <a href="{{ route('transaksi.pembayaran', $transaksi->id) }}">Pembayaran</a> untuk mengubah status transaksi</small>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="diskon">Diskon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon" value="{{ old('diskon', $transaksi->diskon) }}" min="0">
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
                                <input type="number" class="form-control" id="total" name="total" value="{{ old('total', $transaksi->total) }}" readonly>
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
                                <input type="number" class="form-control" id="total_bayar" name="total_bayar" value="{{ old('total_bayar', $transaksi->total_bayar) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="modalTambahProduk" tabindex="-1" role="dialog" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahProdukLabel">Tambah Produk ke Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('transaksi.tambah-produk', $transaksi->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn-sm" id="tambah-produk-modal">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabel-produk-modal">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Panjang</th>
                                        <th>Lebar</th>
                                        <th>Luas</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris produk akan ditambahkan di sini -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total:</th>
                                        <th id="total-modal">Rp 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Template untuk baris produk baru di modal -->
    <template id="template-row-produk">
        <tr class="baris-produk-modal">
            <td>
                <select class="form-control produk-select-modal" name="produk_id[]" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" data-harga="{{ $produk->harga_jual }}">
                            {{ $produk->nama }} - Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" class="form-control panjang-input-modal" name="panjang[]" value="0" min="0" step="0.01" placeholder="Panjang" required>
            </td>
            <td>
                <input type="number" class="form-control lebar-input-modal" name="lebar[]" value="0" min="0" step="0.01" placeholder="Lebar" required>
            </td>
            <td>
                <input type="number" class="form-control luas-input-modal" name="luas[]" value="0" min="0" step="0.01" placeholder="Luas (otomatis)">
            </td>
            <td>
                <input type="number" class="form-control qty-input-modal" name="qty[]" value="1" min="0" step="0.01" placeholder="Qty">
            </td>
            <td>
                <input type="number" class="form-control harga-input-modal" name="harga[]" value="0" readonly>
            </td>
            <td>
                <input type="number" class="form-control jumlah-input-modal" name="jumlah[]" value="0" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm hapus-produk-modal">
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
            // Update total bayar saat diskon berubah
            $('#diskon').on('input', function() {
                hitungTotalBayar();
            });
            
            // Fungsi untuk menghitung total bayar
            function hitungTotalBayar() {
                const total = parseFloat($('#total').val()) || 0;
                const diskon = parseFloat($('#diskon').val()) || 0;
                const totalBayar = total - diskon;
                
                $('#total_bayar').val(totalBayar);
            }
            
            // Tambah baris produk di modal
            $('#tambah-produk-modal').click(function() {
                const template = document.querySelector('#template-row-produk');
                const clone = document.importNode(template.content, true);
                $('#tabel-produk-modal tbody').append(clone);
                
                hitungTotalModal();
            });
            
            // Hapus baris produk di modal
            $(document).on('click', '.hapus-produk-modal', function() {
                $(this).closest('tr').remove();
                hitungTotalModal();
            });
            
            // Update harga saat produk dipilih di modal
            $(document).on('change', '.produk-select-modal', function() {
                const row = $(this).closest('tr');
                const harga = $(this).find(':selected').data('harga') || 0;
                row.find('.harga-input-modal').val(harga);
                updateJumlahModal(row);
            });
            
            // Update luas dan jumlah saat panjang atau lebar berubah di modal
            $(document).on('input', '.panjang-input-modal, .lebar-input-modal', function() {
                const row = $(this).closest('tr');
                updateLuasModal(row);
                updateJumlahModal(row);
            });
            
            // Update jumlah saat luas berubah manual di modal
            $(document).on('input', '.luas-input-modal', function() {
                const row = $(this).closest('tr');
                updateJumlahModal(row);
            });
            
            // Update jumlah saat qty berubah di modal
            $(document).on('input', '.qty-input-modal', function() {
                const row = $(this).closest('tr');
                updateJumlahModal(row);
            });
            
            // Fungsi untuk menghitung luas dari panjang x lebar di modal
            function updateLuasModal(row) {
                const panjang = parseFloat(row.find('.panjang-input-modal').val()) || 0;
                const lebar = parseFloat(row.find('.lebar-input-modal').val()) || 0;
                
                // Luas adalah hasil dari panjang x lebar
                const luas = panjang * lebar;
                row.find('.luas-input-modal').val(luas.toFixed(2));
            }
            
            
            // Fungsi untuk menghitung jumlah per baris di modal
            function updateJumlahModal(row) {
                const luas = parseFloat(row.find('.luas-input-modal').val()) || 0;
                const qty = parseFloat(row.find('.qty-input-modal').val()) || 0;
                const harga = parseFloat(row.find('.harga-input-modal').val()) || 0;
                
                // Jumlah adalah luas x harga x qty
                const jumlah = luas * harga * qty;
                row.find('.jumlah-input-modal').val(jumlah);
                hitungTotalModal();
            }
            
            // Fungsi untuk menghitung total keseluruhan di modal
            function hitungTotalModal() {
                let total = 0;
                $('.jumlah-input-modal').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                
                $('#total-modal').text('Rp ' + total.toLocaleString('id-ID'));
            }
            
            // Tambahkan baris pertama saat modal dibuka
            $('#modalTambahProduk').on('shown.bs.modal', function() {
                if ($('#tabel-produk-modal tbody tr').length === 0) {
                    $('#tambah-produk-modal').click();
                }
            });
            
            // Reset modal saat ditutup
            $('#modalTambahProduk').on('hidden.bs.modal', function() {
                $('#tabel-produk-modal tbody').empty();
            });
        });
    </script>
@stop
