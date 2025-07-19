<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventaris = Inventaris::latest()->paginate(10);

        return view('inventaris.index', compact('inventaris'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Menampilkan form tambah inventaris
        return view('inventaris.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan ke database
        Inventaris::create([
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return view('inventaris.edit', compact('inventaris'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $inventaris = Inventaris::findOrFail($id);

        $inventaris->update([
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }
}
