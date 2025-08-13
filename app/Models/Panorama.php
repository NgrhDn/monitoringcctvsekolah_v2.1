<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panorama extends Model
{
    use HasFactory;

    protected $table = 'panorama';

    protected $fillable = [
        'namaWilayah',
        'namaTitik',
        'link',
    ];
}
