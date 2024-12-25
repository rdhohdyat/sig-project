<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'fasilitas';

    /**
     * Kolom-kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'no',
        'kategori',
        'kecamatan',
        'nama',
        'alamat',
        'jam_buka',
        'jam_tutup',
        'longitude',
        'latitude',
        'foto',
        'keterangan',
    ];

    /**
     * Kolom-kolom yang akan disembunyikan saat serialisasi (opsional).
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
