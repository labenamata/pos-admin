<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukExport;

class LaporanController extends Controller
{
    public function transaksi(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        $status = $request->filled('status') ? $request->status : '';
        
        // Query transaksi
        $query = Transaksi::with(['detailTransaksi.produk.satuan', 'detailTransaksi.produk.kategori'])
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->orderBy('tanggal', 'desc');
        
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
        
        $transaksis = $query->get();
        
        // Hitung total
        $totalNilaiTransaksi = $transaksis->sum('total_bayar');
        $totalTransaksi = $transaksis->count();
        
        // Hitung total per status
        $totalPerStatus = [
            'selesai' => $transaksis->where('status', 'selesai')->sum('total_bayar'),
            'pending' => $transaksis->where('status', 'pending')->sum('total_bayar'),
            'batal' => $transaksis->where('status', 'batal')->sum('total_bayar'),
        ];
        
        // Hitung jumlah per status
        $jumlahPerStatus = [
            'selesai' => $transaksis->where('status', 'selesai')->count(),
            'pending' => $transaksis->where('status', 'pending')->count(),
            'batal' => $transaksis->where('status', 'batal')->count(),
        ];
        
        return view('laporan.transaksi', compact(
            'transaksis',
            'tanggal_mulai',
            'tanggal_akhir',
            'status',
            'totalNilaiTransaksi',
            'totalTransaksi',
            'totalPerStatus',
            'jumlahPerStatus'
        ));
    }
    
    public function detailTransaksi(Request $request, $id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk.satuan', 'detailTransaksi.produk.kategori'])
            ->findOrFail($id);
            
        return view('laporan.detail_transaksi', compact('transaksi'));
    }
    
    public function cetakLaporan(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        $status = $request->filled('status') ? $request->status : '';
        
        // Query transaksi
        $query = Transaksi::with(['detailTransaksi.produk.satuan', 'detailTransaksi.produk.kategori'])
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->orderBy('tanggal', 'desc');
        
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
        
        $transaksis = $query->get();
        
        // Hitung total
        $totalNilaiTransaksi = $transaksis->sum('total_bayar');
        $totalTransaksi = $transaksis->count();
        
        // Hitung total per status
        $totalPerStatus = [
            'selesai' => $transaksis->where('status', 'selesai')->sum('total_bayar'),
            'pending' => $transaksis->where('status', 'pending')->sum('total_bayar'),
            'batal' => $transaksis->where('status', 'batal')->sum('total_bayar'),
        ];
        
        // Hitung jumlah per status
        $jumlahPerStatus = [
            'selesai' => $transaksis->where('status', 'selesai')->count(),
            'pending' => $transaksis->where('status', 'pending')->count(),
            'batal' => $transaksis->where('status', 'batal')->count(),
        ];
        
        return view('laporan.cetak', compact(
            'transaksis',
            'tanggal_mulai',
            'tanggal_akhir',
            'status',
            'totalNilaiTransaksi',
            'totalTransaksi',
            'totalPerStatus',
            'jumlahPerStatus'
        ));
    }
    
    public function laporanProduk(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        $kategori_id = $request->filled('kategori_id') ? $request->kategori_id : null;
        
        // Ambil semua kategori untuk filter
        $kategoris = Kategori::orderBy('nama')->get();
        
        // Query penjualan produk
        $query = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->join('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.id as produk_id',
                'produk.nama as nama_produk',
                'kategori.nama as nama_kategori',
                'satuan.nama as nama_satuan',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_penjualan')
            )
            ->whereDate('transaksi.tanggal', '>=', $tanggal_mulai)
            ->whereDate('transaksi.tanggal', '<=', $tanggal_akhir)
            ->where('transaksi.status', '!=', 'batal')
            ->whereNull('transaksi.deleted_at')
            ->groupBy('produk.id', 'produk.nama', 'kategori.nama', 'satuan.nama')
            ->orderByDesc('total_penjualan');
        
        // Filter berdasarkan kategori
        if ($kategori_id) {
            $query->where('produk.kategori_id', $kategori_id);
        }
        
        $penjualanProduk = $query->get();
        
        // Hitung total penjualan
        $totalPenjualan = $penjualanProduk->sum('total_penjualan');
        $totalQty = $penjualanProduk->sum('total_qty');
        
        // Hitung penjualan per kategori
        $penjualanPerKategori = $penjualanProduk->groupBy('nama_kategori')->map(function ($items) {
            return [
                'total_qty' => $items->sum('total_qty'),
                'total_penjualan' => $items->sum('total_penjualan')
            ];
        });
        
        return view('laporan.produk', compact(
            'penjualanProduk',
            'tanggal_mulai',
            'tanggal_akhir',
            'kategori_id',
            'kategoris',
            'totalPenjualan',
            'totalQty',
            'penjualanPerKategori'
        ));
    }
    
    public function cetakLaporanProduk(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        $kategori_id = $request->filled('kategori_id') ? $request->kategori_id : null;
        
        // Ambil semua kategori untuk filter
        $kategoris = Kategori::orderBy('nama')->get();
        $nama_kategori = $kategori_id ? Kategori::find($kategori_id)->nama : 'Semua Kategori';
        
        // Query penjualan produk
        $query = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->join('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.id as produk_id',
                'produk.nama as nama_produk',
                'kategori.nama as nama_kategori',
                'satuan.nama as nama_satuan',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_penjualan')
            )
            ->whereDate('transaksi.tanggal', '>=', $tanggal_mulai)
            ->whereDate('transaksi.tanggal', '<=', $tanggal_akhir)
            ->where('transaksi.status', '!=', 'batal')
            ->whereNull('transaksi.deleted_at')
            ->groupBy('produk.id', 'produk.nama', 'kategori.nama', 'satuan.nama')
            ->orderByDesc('total_penjualan');
        
        // Filter berdasarkan kategori
        if ($kategori_id) {
            $query->where('produk.kategori_id', $kategori_id);
        }
        
        $penjualanProduk = $query->get();
        
        // Hitung total penjualan
        $totalPenjualan = $penjualanProduk->sum('total_penjualan');
        $totalQty = $penjualanProduk->sum('total_qty');
        
        // Hitung penjualan per kategori
        $penjualanPerKategori = $penjualanProduk->groupBy('nama_kategori')->map(function ($items) {
            return [
                'total_qty' => $items->sum('total_qty'),
                'total_penjualan' => $items->sum('total_penjualan')
            ];
        });
        
        return view('laporan.cetak_produk', compact(
            'penjualanProduk',
            'tanggal_mulai',
            'tanggal_akhir',
            'kategori_id',
            'nama_kategori',
            'totalPenjualan',
            'totalQty',
            'penjualanPerKategori'
        ));
    }
    
    public function exportProduk(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        $kategori_id = $request->filled('kategori_id') ? $request->kategori_id : null;
        
        // Ambil nama kategori
        $nama_kategori = $kategori_id ? Kategori::find($kategori_id)->nama : 'Semua Kategori';
        
        // Query penjualan produk
        $query = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->join('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.id as produk_id',
                'produk.nama as nama_produk',
                'kategori.nama as nama_kategori',
                'satuan.nama as nama_satuan',
                'produk.harga_pokok as harga_pokok',
                'produk.harga_jual as harga_jual',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_penjualan')
            )
            ->whereDate('transaksi.tanggal', '>=', $tanggal_mulai)
            ->whereDate('transaksi.tanggal', '<=', $tanggal_akhir)
            ->where('transaksi.status', '!=', 'batal')
            ->whereNull('transaksi.deleted_at')
            ->groupBy('produk.id', 'produk.nama', 'kategori.nama', 'satuan.nama','produk.harga_pokok','produk.harga_jual')
            ->orderByDesc('total_penjualan');
        
        // Filter berdasarkan kategori
        if ($kategori_id) {
            $query->where('produk.kategori_id', $kategori_id);
        }
        
        $penjualanProduk = $query->get();
        
        // Hitung total penjualan
        $totalPenjualan = $penjualanProduk->sum('total_penjualan');
        $totalQty = $penjualanProduk->sum('total_qty');
        
        // Buat nama file
        $fileName = 'laporan_penjualan_produk_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(
            new ProdukExport(
                $penjualanProduk, 
                $tanggal_mulai, 
                $tanggal_akhir, 
                $nama_kategori,
                $totalQty,
                $totalPenjualan
            ), 
            $fileName
        );
    }
}
