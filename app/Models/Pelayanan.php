<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    protected $table = 'pelayanan';

    protected $fillable = [
        'pegawai_id',
        'nama_pelayanan',
        'tanggal_pelayanan',
        'deskripsi',
        'lokasi',
    ];

    // Relasi ke tabel pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
