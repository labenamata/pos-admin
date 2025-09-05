<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default tanggal (bulan ini)
        $tanggal_mulai = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggal_akhir = $request->filled('tanggal_akhir') ? $request->tanggal_akhir : Carbon::now()->format('Y-m-d');
        
        // Total penjualan selesai
        $totalPenjualanSelesai = Transaksi::where('status', 'selesai')
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->sum('total_bayar');
            
        // Total penjualan pending
        $totalPenjualanPending = Transaksi::where('status', 'pending')
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->sum('total_bayar');
            
        // Jumlah transaksi selesai
        $jumlahTransaksiSelesai = Transaksi::where('status', 'selesai')
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->count();
            
        // Jumlah transaksi pending
        $jumlahTransaksiPending = Transaksi::where('status', 'pending')
            ->whereDate('tanggal', '>=', $tanggal_mulai)
            ->whereDate('tanggal', '<=', $tanggal_akhir)
            ->count();
            
        // Penjualan per item
        $penjualanPerItem = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->select(
                'produk.id',
                'produk.nama as nama_produk',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_penjualan')
            )
            ->whereDate('transaksi.tanggal', '>=', $tanggal_mulai)
            ->whereDate('transaksi.tanggal', '<=', $tanggal_akhir)
            ->where('transaksi.status', '!=', 'batal')
            ->where('transaksi.deleted_at', null)
            ->groupBy('produk.id', 'produk.nama')
            ->orderByDesc('total_penjualan')
            ->get();
            
        return view('dashboard', compact(
            'tanggal_mulai', 
            'tanggal_akhir', 
            'totalPenjualanSelesai', 
            'totalPenjualanPending', 
            'jumlahTransaksiSelesai', 
            'jumlahTransaksiPending', 
            'penjualanPerItem'
        ));
    }
}
