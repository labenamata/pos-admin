<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::with(['kategori', 'satuan'])->get();
        return view('produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        return view('produk.create', compact('kategoris', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_pokok' => 'nullable|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Produk::create([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with(['kategori', 'satuan'])->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        return view('produk.edit', compact('produk', 'kategoris', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_pokok' => 'nullable|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $produk->update([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
