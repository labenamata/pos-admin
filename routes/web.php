<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\InformasiTokoController;
use App\Http\Controllers\UserController;



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Resource routes dengan middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('satuan', SatuanController::class)->middleware('role:admin');
    Route::resource('kategori', KategoriController::class)->middleware('role:admin');
    Route::resource('produk', ProdukController::class)->middleware('role:admin');
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('informasi-toko', InformasiTokoController::class)->middleware('role:admin');
    Route::resource('users', UserController::class)->middleware('role:admin');
    
    // Route untuk ganti password
    Route::get('users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.change-password')->middleware('role:admin');
    Route::put('users/{id}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password')->middleware('role:admin');
    
    // Invoice route
    Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');
    Route::get('/transaksi/{id}/export-pdf', [TransaksiController::class, 'exportPDF'])->name('transaksi.export-pdf');
Route::get('/transaksi/{id}/cetak-struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk');
    
    // Pembayaran route
    Route::get('/transaksi/{id}/pembayaran', [TransaksiController::class, 'pembayaran'])->name('transaksi.pembayaran');
    Route::post('/transaksi/{id}/proses-pembayaran', [TransaksiController::class, 'prosesPembayaran'])->name('transaksi.proses-pembayaran');
    
    // Tambah produk ke transaksi yang sudah ada
    Route::post('/transaksi/{id}/tambah-produk', [TransaksiController::class, 'tambahProduk'])->name('transaksi.tambah-produk');
    
    // Laporan routes
    Route::get('/laporan/transaksi', [App\Http\Controllers\LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('/laporan/transaksi/{id}', [App\Http\Controllers\LaporanController::class, 'detailTransaksi'])->name('laporan.detail');
    Route::get('/laporan/cetak', [App\Http\Controllers\LaporanController::class, 'cetakLaporan'])->name('laporan.cetak');
    Route::get('/laporan/produk', [App\Http\Controllers\LaporanController::class, 'laporanProduk'])->name('laporan.produk');
    Route::get('/laporan/produk/cetak', [App\Http\Controllers\LaporanController::class, 'cetakLaporanProduk'])->name('laporan.cetak.produk');
    Route::get('/laporan/produk/export', [App\Http\Controllers\LaporanController::class, 'exportProduk'])->name('laporan.produk.export');
});
