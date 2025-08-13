<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GlobalResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cctv;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Validator;

class ApiSekolahController extends Controller
{
    public function index()
    {
        try {
            $cctvs = Cctv::with(['sekolah', 'wilayah'])
                ->orderBy('wilayah_id')
                ->orderBy('sekolah_id')
                ->orderBy('nama_titik')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'wilayah_id' => $item->wilayah_id, // Perbaikan: Menambahkan wilayah_id
                        'nama_wilayah' => $item->wilayah->nama_wilayah ?? '-',
                        'nama_sekolah' => $item->sekolah->nama_sekolah ?? '-',
                        'nama_titik' => $item->nama_titik,
                        'link' => $item->link_stream,
                        'is_active' => $item->active ? true : false,
                    ];
                });

            if ($cctvs->isEmpty()) {
                return new GlobalResource(false, 'Belum ada data CCTV sekolah yang tersedia.', null);
            }

            return new GlobalResource(true, 'Data CCTV sekolah berhasil dimuat.', $cctvs);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat mengambil data.', null);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|exists:wilayah,id',
                'nama_sekolah' => 'required|string|max:255',
                'nama_titik' => 'required|string|max:255',
                'link_stream' => 'required|url|unique:cctvs,link_stream',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Data yang Anda masukkan tidak valid.', $validator->errors());
            }

            $sekolah = Sekolah::firstOrCreate(
                ['nama_sekolah' => $request->nama_sekolah],
                ['wilayah_id' => $request->wilayah_id]
            );

            // PERBAIKAN: Mengatur status 'active' menjadi TRUE secara eksplisit
            $cctv = Cctv::create([
                'wilayah_id' => $request->wilayah_id,
                'sekolah_id' => $sekolah->id,
                'nama_titik' => $request->nama_titik,
                'link_stream' => $request->link_stream,
                'active' => true, // Selalu true saat membuat data baru
            ]);

            return new GlobalResource(true, 'Data CCTV sekolah berhasil ditambahkan.', $cctv);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage(), null);
        }
    }

    public function show(string $id)
    {
        try {
            $data = Cctv::with(['sekolah', 'wilayah'])->find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data CCTV sekolah tidak ditemukan.', null);
            }

            // Catatan untuk Front-End:
            // Data 'wilayah' di sini berisi informasi wilayah yang terkait dengan CCTV ini.
            // Gunakan `data->wilayah_id` untuk mencocokkan dan memilih opsi yang benar di dropdown Wilayah pada form edit.
            return new GlobalResource(true, 'Detail data CCTV sekolah berhasil dimuat.', $data);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat memuat data CCTV sekolah.', null);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = Cctv::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data CCTV tidak ditemukan.', null);
            }

            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|exists:wilayah,id',
                'nama_sekolah' => 'required|string|max:255',
                'nama_titik' => 'required|string|max:255',
                'link_stream' => 'required|url|unique:cctvs,link_stream,' . $id,
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Data yang Anda masukkan tidak valid.', $validator->errors());
            }

            $sekolah = Sekolah::firstOrCreate(['nama_sekolah' => $request->nama_sekolah]);
            
            // PERBAIKAN: Memastikan status 'active' tidak berubah saat update jika tidak dikirimkan.
            $data->update([
                'wilayah_id' => $request->wilayah_id,
                'sekolah_id' => $sekolah->id,
                'nama_titik' => $request->nama_titik,
                'link_stream' => $request->link_stream,
                'active' => $request->has('active') ? $request->active : $data->active,
            ]);

            return new GlobalResource(true, 'Data CCTV sekolah berhasil diperbarui.', $data);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return new GlobalResource(false, 'Link CCTV sudah digunakan. Silakan gunakan link yang berbeda.', null);
            }
            return new GlobalResource(false, 'Terjadi kesalahan saat memperbarui data CCTV sekolah.', null);
        }
    }

    public function destroy(string $id)
    {
        try {
            $data = Cctv::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data CCTV sekolah tidak ditemukan.', null);
            }

            $data->delete();

            return new GlobalResource(true, 'Data CCTV sekolah berhasil dihapus.', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat menghapus data CCTV sekolah.', null);
        }
    }

    // Fungsi baru untuk mengaktifkan/menonaktifkan banyak data sekaligus
    public function bulkToggle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'exists:cctvs,id',
                'active' => 'required|boolean', // <-- Perubahan: Gunakan 'active'
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Data yang Anda masukkan tidak valid.', $validator->errors());
            }

            Cctv::whereIn('id', $request->ids)->update(['active' => $request->active]); // <-- Perubahan: Gunakan 'active'

            $message = $request->active ? 'Berhasil mengaktifkan semua data.' : 'Berhasil menonaktifkan semua data.';

            return new GlobalResource(true, $message, null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat mengubah status data.', null);
        }
    }
}
