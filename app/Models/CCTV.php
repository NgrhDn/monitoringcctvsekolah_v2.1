<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    use HasFactory;

    protected $table = 'cctvs'; // Pastikan nama tabel benar

    // Tambahkan properti $fillable di sini
    protected $fillable = [
        'wilayah_id',
        'sekolah_id',
        'nama_titik',
        'link_stream',
        'active',
    ];

    // Relasi ke tabel sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // Relasi ke tabel wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}