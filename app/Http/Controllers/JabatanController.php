<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class JabatanController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:menu-jabatan', ['all']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $jabatan = Jabatan::all();
        return view('admin.jabatan.index', [
            'jabatan' => $jabatan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.jabatan.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $this->validate($request, [
            'nm_jabatan' => 'required',
            'gaji_pokok' => 'required',
        ]);

        // Ubah nama jabatan ke lowercase untuk pengecekan dan penyimpanan
        $nm_jabatan = strtolower($request->nm_jabatan);

        // Cek apakah jabatan dengan nama lowercase sudah ada
        $exists = Jabatan::whereRaw('LOWER(nm_jabatan) = ?', [$nm_jabatan])->exists();
        if ($exists) {
            Alert::error('Gagal', 'Nama jabatan sudah ada!');
            return redirect()->back()->withInput();
        }

        // Proses angka gaji pokok
        $rupiah_string = $request->gaji_pokok;
        $jumlah_string = preg_replace("/[^0-9]/", "", $rupiah_string);
        $gaji_pokok = (int) $jumlah_string;

        // Simpan data
        Jabatan::create([
            'nm_jabatan' => $nm_jabatan, // sudah lowercase
            'gaji_pokok' => $gaji_pokok,
        ]);

        Alert::success('Success', 'Berhasil Input Data!');
        return redirect('jabatan');
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
    public function edit($data)
    {
        //
        $id = Crypt::decryptString($data);
        $jabatan = Jabatan::find($id);
        return view('admin.jabatan.edit', [
            'jabatan' => $jabatan,
            'id' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $data)
    {
        //

        $id = Crypt::decryptString($data);

        $this->validate($request, [
            'nm_jabatan' => 'required',
            'gaji_pokok' => 'required',
        ]);

        $rupiah_string = $request->gaji_pokok;
        $jumlah_string = preg_replace("/[^0-9]/", "", $rupiah_string);
        $gaji_pokok = (int) $jumlah_string;


        $jabatan = Jabatan::find($id);

        $jabatan->nm_jabatan = $request->nm_jabatan;
        $jabatan->gaji_pokok = $gaji_pokok;
        $jabatan->save();

        Alert::success('success', ' Berhasil Update Data !');
        return redirect('/jabatan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($data)
    {
        //
        $id = Crypt::decryptString($data);
        $jabatan = Jabatan::find($id);
        $jabatan->delete();

        Alert::success('success', ' Berhasil Hapus Data !');
        return redirect('/jabatan');
    }
}
