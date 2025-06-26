<?php

namespace App\Http\Controllers;

use App\Imports\PresensiCsvImport;
use App\Models\Pegawai;
use App\Models\Presensi_harian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PresensiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:menu-presensi', ['all']);
    }

    public function index()
    {
        //
        $today = \Carbon\Carbon::now(); //Current Date and Time

        $dari = date("Y-m-01");
        $ke = \Carbon\Carbon::parse($today)->endOfMonth()->toDateString();;

        $presensi = Presensi_harian::whereBetween('tanggal', [$dari, $ke])->paginate(20);
        return view('admin.presensi.index', [
            'presensi' => $presensi,
            'dari' => $dari,
            'ke' => $ke,
        ]);
    }

    public function tglPresensi(Request $request)
    {
        $dari = $request->dari;
        $ke = $request->ke;
        $presensi = Presensi_harian::whereBetween('tanggal', [$dari, $ke])->paginate(20);
        $presensi->appends($request->all());
        return view('admin.presensi.index', [
            'presensi' => $presensi,
            'dari' => $dari,
            'ke' => $ke,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $pegawai = Pegawai::pluck('nama', 'id'); // Untuk admin

        // Untuk user non-admin
        $pegawaiLogin = Pegawai::where('id', $user->id)->first(); // atau pakai relasi

        return view('admin.presensi.create', compact('pegawai', 'pegawaiLogin', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi awal
        $this->validate($request, [
            'id_pegawai' => 'required',
            'tanggal' => 'required|date',
            'ket' => 'required',
        ]);

        // Cek apakah sudah ada presensi di tanggal yang sama
        $cek = Presensi_harian::where('id_pegawai', $request->id_pegawai)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($cek) {
            Alert::error('Sudah Absen', 'Presensi untuk tanggal ini sudah ada.');
            return redirect()->back()
                ->withInput();
        }

        // Simpan jika belum ada
        Presensi_harian::create([
            'id_pegawai' => $request->id_pegawai,
            'tanggal' => $request->tanggal,
            'ket' => $request->ket,
            'jam_dtg' => $request->jam_dtg,
            'jam_plg' => $request->jam_plg,
            'is_wfh' => $request->is_wfh,
        ]);

        Alert::success('success', 'Berhasil Input Data!');
        return redirect('presensi');
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
        $presensi = Presensi_harian::find($id);
        $pegawai = Pegawai::pluck('nama', 'id');

        return view('admin.presensi.edit', [
            'id' => $data,
            'presensi' => $presensi,
            'pegawai' => $pegawai
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
            'id_pegawai' => 'required',
            'tanggal' => 'required',
            'ket' => 'required',
        ]);

        $presensi = Presensi_harian::find($id);

        $presensi->id_pegawai = $request->id_pegawai;
        $presensi->tanggal = $request->tanggal;
        $presensi->ket = $request->ket;
        $presensi->jam_dtg = $request->jam_dtg;
        $presensi->jam_plg = $request->jam_plg;
        $presensi->is_wfh = $request->is_wfh;
        $presensi->save();

        Alert::success('success', ' Berhasil Update Data !');
        return redirect('presensi');
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
        $presensi = Presensi_harian::find($id);
        $presensi->delete();

        Alert::success('success', ' Berhasil Hapus Data !');
        return redirect('presensi');
    }

    public function import()
    {
        try {
            Excel::import(new PresensiCsvImport, request()->file('file'));
            Alert::success('success', ' Berhasil Import Data !!');
            return redirect('presensi');
        } catch (\Exception $ex) {
            Alert::error('error', 'Terjadi Kesalahan, Cek Kembali Anda & Lihat Cara Import!');
            return redirect(route('presensi.create'));
        }
    }

    public function download()
    {
        $filePath = public_path("/template_presensi/Presensi.xlsx");
        $headers = ['Content-Type: application/pdf'];
        $fileName = 'Presensi.xlsx';

        return response()->download($filePath, $fileName, $headers);
    }

    public function pulang($id)
    {
        $presensi = Presensi_harian::findOrFail($id);
        $now = Carbon::now('Asia/Makassar'); // ganti sesuai timezone kamu

        // Jika sudah absen pulang
        if ($presensi->jam_plg) {
            Alert::info('Info', 'Anda sudah absen pulang sebelumnya.');
            return redirect()->back();
        }

        // Validasi: Belum waktunya pulang
        if ($now->format('H:i') < '16:30') {
            Alert::warning('Terlalu Cepat', 'Belum waktunya absen pulang.');
            return redirect()->back();
        }

        // Simpan jam pulang
        $presensi->jam_plg = $now->format('H:i');
        $presensi->save();

        Alert::success('Berhasil', 'Absen pulang berhasil dicatat.');
        return redirect()->back();
    }
}
