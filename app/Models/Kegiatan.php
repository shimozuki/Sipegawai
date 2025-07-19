<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama_kegiatan',
        'tanggal_kegiatan',
        'deskripsi',
        'lokasi',
        'pegawai_id',
    ];

    // (Opsional) Cast jika ingin format tanggal otomatis
    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];
}
