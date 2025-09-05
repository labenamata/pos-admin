<?php

namespace App\Http\Controllers;

use App\Models\InformasiToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InformasiTokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informasiToko = InformasiToko::first();
        return view('informasi_toko.index', compact('informasiToko'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $informasiToko = InformasiToko::first();
        if ($informasiToko) {
            return redirect()->route('informasi-toko.edit', $informasiToko->id)
                ->with('warning', 'Informasi toko sudah ada, silahkan edit data yang sudah ada.');
        }
        return view('informasi_toko.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/logo', $logoName);
            $data['logo'] = $logoName;
        }

        InformasiToko::create($data);

        return redirect()->route('informasi-toko.index')
            ->with('success', 'Informasi toko berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $informasiToko = InformasiToko::findOrFail($id);
        return view('informasi_toko.show', compact('informasiToko'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $informasiToko = InformasiToko::findOrFail($id);
        return view('informasi_toko.edit', compact('informasiToko'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $informasiToko = InformasiToko::findOrFail($id);
        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($informasiToko->logo) {
                Storage::delete('public/logo/' . $informasiToko->logo);
            }

            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/logo', $logoName);
            $data['logo'] = $logoName;
        }

        $informasiToko->update($data);

        return redirect()->route('informasi-toko.index')
            ->with('success', 'Informasi toko berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $informasiToko = InformasiToko::findOrFail($id);
        
        // Hapus logo jika ada
        if ($informasiToko->logo) {
            Storage::delete('public/logo/' . $informasiToko->logo);
        }
        
        $informasiToko->delete();
        
        return redirect()->route('informasi-toko.index')
            ->with('success', 'Informasi toko berhasil dihapus.');
    }
}
