<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah'; // Pastikan nama tabel benar

    // Tambahkan properti $fillable
    protected $fillable = [
        'nama_sekolah',
        'wilayah_id',
    ];

    // Relasi ke tabel wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    // Relasi ke tabel cctvs (jika dibutuhkan untuk relasi hasMany)
    public function cctvs()
    {
        return $this->hasMany(Cctv::class, 'sekolah_id');
    }
}