<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Kegiatan::query();

        // Jika user adalah admin (role 1), bisa filter semua pegawai
        if ($user->id_role == 1) {
            if ($request->filled('pegawai_id')) {
                $query->where('pegawai_id', $request->pegawai_id);
            }

            $daftarPegawai = Pegawai::orderBy('nama')->get();
        } else {
            // Jika bukan admin (misal pegawai), hanya tampilkan kegiatan miliknya
            $query->where('pegawai_id', $user->id);
            $daftarPegawai = collect(); // kosong karena tidak ditampilkan di view
        }

        $kegiatan = $query->latest()->paginate(10)->withQueryString();

        return view('kegiatan.index', compact('kegiatan', 'user', 'daftarPegawai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pegawai_id = auth()->user()->id;

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kegiatan::create([
            'pegawai_id'       => $pegawai_id,
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'deskripsi'        => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
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
        $kegiatan = Kegiatan::findOrFail($id);

        // Opsional: pastikan hanya pemilik yang bisa edit
        if ($kegiatan->pegawai_id != auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('kegiatan.edit', compact('kegiatan'));
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
            'nama_kegiatan'     => 'required|string|max:255',
            'tanggal_kegiatan'  => 'required|date',
            'lokasi'            => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        // Opsional: pastikan hanya pemilik yang bisa update
        if ($kegiatan->pegawai_id != auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $kegiatan->update([
            'nama_kegiatan'     => $request->nama_kegiatan,
            'tanggal_kegiatan'  => $request->tanggal_kegiatan,
            'lokasi'            => $request->lokasi,
            'deskripsi'         => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Opsional: pastikan hanya pemilik yang bisa hapus
        if ($kegiatan->pegawai_id != auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
