<?php

namespace App\Exports;

use App\Models\Panorama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PanoramaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Panorama::select('id', 'namaWilayah', 'namaTitik', 'link', 'status')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Wilayah', 'Titik', 'Link', 'Status'];
    }
}
