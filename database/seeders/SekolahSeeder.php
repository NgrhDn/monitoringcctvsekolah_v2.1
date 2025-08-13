<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 700; $i++) {
            $data[] = [
                'namaWilayah' => 'Wilayah ' . rand(1, 10),
                'namaSekolah' => 'Sekolah ' . $i,
                'namaTitik' => 'Titik ' . rand(1, 5),
                'link' => 'http://cctv.sekolah' . $i . '.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('sekolah')->insert($data);
    }
}

