<?php

namespace App\Exports;

use App\Models\Cctv; // Menggunakan model Cctv
use App\Models\Sekolah;
use App\Models\Wilayah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SekolahExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Query untuk mengambil data CCTV, nama sekolah, dan nama wilayah
        $data = DB::table('cctvs')
            ->join('sekolah', 'cctvs.sekolah_id', '=', 'sekolah.id')
            ->join('wilayah', 'cctvs.wilayah_id', '=', 'wilayah.id')
            ->select(
                'wilayah.nama_wilayah as namaWilayah',
                'sekolah.nama_sekolah as namaSekolah',
                'cctvs.nama_titik as namaTitik',
                'cctvs.link_stream as link'
            )
            ->orderBy('wilayah.nama_wilayah')
            ->orderBy('sekolah.nama_sekolah')
            ->orderBy('cctvs.nama_titik')
            ->get();

        // Mengubah collection menjadi format yang diinginkan dengan nomor urut
        $exportData = [];
        $no = 1;
        foreach ($data as $row) {
            $exportData[] = [
                'No'            => $no++,
                'Nama Wilayah'  => $row->namaWilayah,
                'Nama Sekolah'  => $row->namaSekolah,
                'Nama Titik'    => $row->namaTitik,
                'Link'          => $row->link,
            ];
        }

        return new Collection($exportData);
    }

    public function headings(): array
    {
        return ['No', 'Nama Wilayah', 'Nama Sekolah', 'Nama Titik', 'Link'];
    }
}
