<?php

namespace App\Http\Controllers;

use App\Models\cctv; // Ubah ini ke model cctv
use App\Models\sekolah;
use App\Models\wilayah;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ManualImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            unset($rows[0]); // header

            foreach ($rows as $row) {
                // Pastikan baris data memiliki setidaknya 5 kolom
                if (count($row) < 5) continue;

                $namaWilayah = $row[1];
                $namaSekolah = $row[2];
                $namaTitik   = $row[3];
                $linkStream  = $row[4];

                // Cari atau buat wilayah
                $wilayah = Wilayah::firstOrCreate(['nama_wilayah' => $namaWilayah]);

                // Cari atau buat sekolah, dan hubungkan dengan wilayah
                $sekolah = Sekolah::firstOrCreate(['nama_sekolah' => $namaSekolah], ['wilayah_id' => $wilayah->id]);

                // Buat entri baru di tabel cctvs dengan ID sekolah dan wilayah
                cctv::create([
                    'sekolah_id'  => $sekolah->id,
                    'wilayah_id'  => $wilayah->id,
                    'nama_titik'  => $namaTitik,
                    'link_stream' => $linkStream,
                    'active'      => 1,
                ]);
            }

            return back()->with('swal', [
                'status' => 'success',
                'message' => 'Import berhasil!'
            ]);
        } catch (\Exception $e) {
            return back()->with('swal', [
                'status' => 'error',
                'message' => 'Import gagal: ' . $e->getMessage()
            ]);
        }
    }
}
