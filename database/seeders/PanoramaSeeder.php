<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanoramaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('panorama')->insert([
            [
                'namaWilayah' => 'KOMINFO SLEMAN 1',
                'namaTitik' => 'Bundaran UGM 10',
                'link' => 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM1',
                'status' => 'offline',
                'created_at' => Carbon::parse('2024-11-21 00:17:36'),
                'updated_at' => Carbon::parse('2025-02-10 11:28:10'),
            ],
            [
                'namaWilayah' => 'KOMINFO SLEMAN 1',
                'namaTitik' => 'Bundaran UGM 2',
                'link' => 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM2',
                'status' => 'offline',
                'created_at' => Carbon::parse('2024-11-21 00:18:18'),
                'updated_at' => Carbon::parse('2024-11-21 00:18:18'),
            ],
            [
                'namaWilayah' => 'KOMINFO SLEMAN 1',
                'namaTitik' => 'Bundaran UGM 3',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula',
                'status' => 'offline',
                'created_at' => Carbon::parse('2024-11-21 00:18:52'),
                'updated_at' => Carbon::parse('2025-04-15 19:24:13'),
            ],
        ]);
    }
}

