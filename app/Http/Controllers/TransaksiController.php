<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use App\Models\InformasiToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::orderBy('tanggal', 'desc');
        
        // Default menampilkan hanya status pending
        if (!$request->filled('status') || $request->status == 'pending') {
            $query->where('status', 'pending');
        } elseif ($request->status == 'semua') {
            // Tidak perlu filter jika ingin menampilkan semua status
        } else {
            // Filter berdasarkan status jika ada di request
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        
        // Filter berdasarkan tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan nama pelanggan
        if ($request->filled('nama_pelanggan')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        
        $transaksis = $query->get();
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::all();
        return view('transaksi.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nama_pelanggan' => 'nullable|string|max:255',
            'total' => 'required|integer|min:0',
            'diskon' => 'nullable|integer|min:0',
            'total_bayar' => 'required|integer|min:0',
            'note' => 'nullable|string',
            'status' => 'required|string|in:pending,selesai,batal',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produk,id',
            'panjang' => 'required|array',
            'panjang.*' => 'numeric|min:0',
            'lebar' => 'required|array',
            'lebar.*' => 'numeric|min:0',
            'luas' => 'nullable|array',
            'luas.*' => 'nullable|numeric|min:0',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:0.01',
            'harga' => 'required|array',
            'harga.*' => 'integer|min:0',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaksi = Transaksi::create([
                'tanggal' => $request->tanggal,
                'nama_pelanggan' => $request->nama_pelanggan,
                'total' => $request->total,
                'diskon' => $request->diskon,
                'total_bayar' => $request->total_bayar,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            // Simpan detail transaksi
            $produk_ids = $request->produk_id;
            $panjangs = $request->panjang;
            $lebars = $request->lebar;
            $luass = $request->luas ?? [];
            $qtys = $request->qty;
            $hargas = $request->harga;
            $jumlahs = $request->jumlah;

            foreach ($produk_ids as $key => $produk_id) {
                // Hitung luas otomatis jika tidak diisi
                $luas = isset($luass[$key]) && $luass[$key] ? $luass[$key] : ($panjangs[$key] * $lebars[$key]);
                
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk_id,
                    'panjang' => $panjangs[$key],
                    'lebar' => $lebars[$key],
                    'luas' => $luas,
                    'qty' => $qtys[$key],
                    'harga' => $hargas[$key],
                    'jumlah' => $jumlahs[$key],
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk')->findOrFail($id);
        $produks = Produk::all();
        return view('transaksi.edit', compact('transaksi', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nama_pelanggan' => 'nullable|string|max:255',
            'total' => 'required|integer|min:0',
            'diskon' => 'nullable|integer|min:0',
            'total_bayar' => 'required|integer|min:0',
            'note' => 'nullable|string',
            'status' => 'required|string|in:pending,selesai,batal',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $transaksi->update([
            'tanggal' => $request->tanggal,
            'nama_pelanggan' => $request->nama_pelanggan,
            'total' => $request->total,
            'diskon' => $request->diskon,
            'total_bayar' => $request->total_bayar,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Hapus detail transaksi terlebih dahulu
            DetailTransaksi::where('transaksi_id', $id)->delete();
            
            // Hapus transaksi
            $transaksi->delete();
            
            DB::commit();
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menampilkan invoice transaksi
     */
    public function invoice(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk.satuan')->findOrFail($id);
        $toko = InformasiToko::first();
        
        return view('transaksi.invoice', compact('transaksi', 'toko'));
    }
    
    /**
     * Export invoice transaksi ke PDF
     */
    public function exportPDF(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk.satuan')->findOrFail($id);
        $toko = InformasiToko::first();
        
        $pdf = PDF::loadView('transaksi.invoice-pdf', compact('transaksi', 'toko'));
        return $pdf->download('invoice-' . $transaksi->id . '.pdf');
    }
    
    /**
     * Cetak struk transaksi
     */
    public function cetakStruk(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk.satuan')->findOrFail($id);
        $toko = InformasiToko::first();
        
        return view('transaksi.struk', compact('transaksi', 'toko'));
    }
    
    /**
     * Menampilkan halaman pembayaran transaksi
     */
    public function pembayaran(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk.satuan')->findOrFail($id);
        
        return view('transaksi.pembayaran', compact('transaksi'));
    }
    
    /**
     * Memproses pembayaran dan mengubah status transaksi
     */
    public function prosesPembayaran(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'metode_pembayaran' => 'required|string|in:tunai,transfer',
            'catatan' => 'nullable|string',
            'cetak_struk' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $transaksi->update([
            'status' => 'selesai', // Status otomatis menjadi selesai
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);
        
        // Cek apakah user ingin mencetak struk
        if ($request->has('cetak_struk')) {
            // Redirect ke halaman index dengan pesan sukses dan script untuk membuka struk di tab baru
            return redirect()->route('transaksi.index')
                ->with('success', 'Pembayaran berhasil diproses dengan metode ' . ucfirst($request->metode_pembayaran) . ' dan status transaksi diubah menjadi Selesai')
                ->with('print_struk', route('transaksi.cetak-struk', $transaksi->id));
        }
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Pembayaran berhasil diproses dengan metode ' . ucfirst($request->metode_pembayaran) . ' dan status transaksi diubah menjadi Selesai');
    }
    
    /**
     * Menambahkan produk baru ke transaksi yang sudah ada
     */
    public function tambahProduk(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Validasi status transaksi harus pending
        if ($transaksi->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Produk hanya dapat ditambahkan jika status transaksi masih pending');
        }
        
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produk,id',
            'panjang' => 'required|array',
            'panjang.*' => 'numeric|min:0',
            'lebar' => 'required|array',
            'lebar.*' => 'numeric|min:0',
            'luas' => 'nullable|array',
            'luas.*' => 'nullable|numeric|min:0',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:0',
            'harga' => 'required|array',
            'harga.*' => 'numeric|min:0',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        try {
            $produk_ids = $request->produk_id;
            $panjangs = $request->panjang;
            $lebars = $request->lebar;
            $luass = $request->luas ?? [];
            $qtys = $request->qty;
            $hargas = $request->harga;
            $jumlahs = $request->jumlah;
            
            $totalBaru = 0;
            
            foreach ($produk_ids as $key => $produk_id) {
                // Hitung luas otomatis jika tidak diisi
                $luas = isset($luass[$key]) && $luass[$key] ? $luass[$key] : ($panjangs[$key] * $lebars[$key]);
                
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk_id,
                    'panjang' => $panjangs[$key],
                    'lebar' => $lebars[$key],
                    'luas' => $luas,
                    'qty' => $qtys[$key],
                    'harga' => $hargas[$key],
                    'jumlah' => $jumlahs[$key],
                ]);
                
                $totalBaru += $jumlahs[$key];
            }
            
            // Update total dan total_bayar transaksi
            $totalLama = $transaksi->total;
            $totalBaru = $totalLama + $totalBaru;
            $diskon = $transaksi->diskon ?? 0;
            $totalBayar = $totalBaru - $diskon;
            
            $transaksi->update([
                'total' => $totalBaru,
                'total_bayar' => $totalBayar
            ]);
            
            DB::commit();
            return redirect()->route('transaksi.edit', $id)
                ->with('success', 'Produk berhasil ditambahkan ke transaksi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
