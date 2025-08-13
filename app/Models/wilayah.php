<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_wilayah',
    ];

    // Relasi ke tabel sekolah (jika dibutuhkan)
    public function sekolahs()
    {
        return $this->hasMany(Sekolah::class, 'wilayah_id');
    }

    // Relasi ke tabel cctvs (jika dibutuhkan)
    public function cctvs()
    {
        return $this->hasMany(Cctv::class, 'wilayah_id');
    }
}
