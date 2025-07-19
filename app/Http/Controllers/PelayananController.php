<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelayananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pelayanan::query();

        if ($user->id_role == 1) {
            if ($request->filled('pegawai_id')) {
                $query->where('pegawai_id', $request->pegawai_id);
            }
            $daftarPegawai = Pegawai::orderBy('nama')->get();
        } else {
            $query->where('pegawai_id', $user->id);
            $daftarPegawai = collect(); // kosong untuk non-admin
        }

        $pelayanan = $query->latest()->paginate(10)->withQueryString();

        return view('pelayanan.index', compact('pelayanan', 'user', 'daftarPegawai'));
    }

    public function create()
    {
        return view('pelayanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelayanan' => 'required|string|max:255',
            'tanggal_pelayanan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
        ]);

        Pelayanan::create([
            'pegawai_id' => Auth::user()->id,
            'nama_pelayanan' => $request->nama_pelayanan,
            'tanggal_pelayanan' => $request->tanggal_pelayanan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        if (Auth::user()->id != $pelayanan->pegawai_id && Auth::user()->id_role != 1) {
            abort(403, 'Akses ditolak.');
        }

        return view('pelayanan.edit', compact('pelayanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelayanan' => 'required|string|max:255',
            'tanggal_pelayanan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $pelayanan = Pelayanan::findOrFail($id);

        if (Auth::user()->id != $pelayanan->pegawai_id && Auth::user()->id_role != 1) {
            abort(403, 'Akses ditolak.');
        }

        $pelayanan->update([
            'nama_pelayanan' => $request->nama_pelayanan,
            'tanggal_pelayanan' => $request->tanggal_pelayanan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        if (Auth::user()->id != $pelayanan->pegawai_id && Auth::user()->id_role != 1) {
            abort(403, 'Akses ditolak.');
        }

        $pelayanan->delete();

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil dihapus.');
    }
}
