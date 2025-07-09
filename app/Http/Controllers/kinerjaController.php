<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Peraturan;
use App\Models\Presensi_harian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class kinerjaController extends Controller
{
    public function getGrafikKinerja(Request $request, $id_peg)
    {
        $tahun = $request->query('tahun', now()->year);
        $pegawai = Pegawai::findOrFail($id_peg);
        $peraturan = Peraturan::latest('id')->first();

        $Telat = [];
        $Awal = [];
        $Hadir = [];
        $Alpha = [];
        $Cuti = [];

        for ($i = 1; $i <= 12; $i++) {
            $Telat[] = Presensi_harian::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->where('jam_dtg', '>', $peraturan->jam_masuk)
                ->count();

            $Awal[] = Presensi_harian::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->where('jam_plg', '<', $peraturan->jam_plg)
                ->count();

            $Hadir[] = Presensi_harian::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->where('ket', 'Hadir')
                ->count();

            $Alpha[] = Presensi_harian::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->where('ket', 'Alpha')
                ->count();

            $Cuti[] = Presensi_harian::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->where('ket', 'Cuti')
                ->count();
        }

        return response()->json([
            'pegawai' => $pegawai->nama,
            'tahun' => $tahun,
            'grafik' => [
                'Telat' => $Telat,
                'PulangAwal' => $Awal,
                'Hadir' => $Hadir,
                'Alpha' => $Alpha,
                'Cuti' => $Cuti,
            ]
        ]);
    }

    public function getKinerjaPerBulan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Total seluruh pegawai per bulan
        $kinerjaTotal = Presensi_harian::select(
            DB::raw("MONTH(tanggal) as bulan"),
            DB::raw("SUM(CASE WHEN ket = 'Hadir' THEN 1 ELSE 0 END) as hadir"),
            DB::raw("SUM(CASE WHEN ket = 'Cuti' THEN 1 ELSE 0 END) as cuti"),
            DB::raw("SUM(CASE WHEN ket = 'Alpha' THEN 1 ELSE 0 END) as alpha")
        )
            ->whereYear('tanggal', $tahun)
            ->groupBy(DB::raw("MONTH(tanggal)"))
            ->orderBy('bulan')
            ->get();

        return response()->json([
            'data' => $kinerjaTotal
        ]);
    }
}
