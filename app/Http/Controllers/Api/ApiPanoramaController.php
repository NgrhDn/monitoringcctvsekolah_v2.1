<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GlobalResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panorama;
use Illuminate\Support\Facades\Validator;

class ApiPanoramaController extends Controller
{
    public function index()
    {
        try {
            $panorama = Panorama::all();

            if ($panorama->isEmpty()) {
                return new GlobalResource(false, 'Belum ada data CCTV Panorama yang tersedia.', null);
            }

            return new GlobalResource(true, 'Daftar data CCTV Panorama berhasil dimuat.', $panorama);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat memuat data. Silakan coba beberapa saat lagi.', null);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaWilayah' => 'required|string|max:255',
                'namaTitik' => 'required|string|max:255',
                'link' => 'required|url',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal. Silakan periksa input Anda.', $validator->errors());
            }

            $panorama = Panorama::create($request->all());

            return new GlobalResource(true, 'Data Panorama berhasil ditambahkan', $panorama);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Duplikat entri
                return new GlobalResource(false, 'Link CCTV sudah terdaftar. Gunakan link yang berbeda.', null);
            }

            // Error query lainnya
            return new GlobalResource(false, 'Terjadi kesalahan pada database. Silakan coba lagi.', null);
        } catch (\Exception $e) {
            // Error umum
            return new GlobalResource(false, 'Terjadi kesalahan. Silakan coba lagi.', null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Panorama::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan', null);
            }

            return new GlobalResource(true, 'Detail Data Panorama', $data);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = Panorama::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan.', null);
            }

            $validator = Validator::make($request->all(), [
                'namaWilayah' => 'required|string|max:255',
                'namaTitik' => 'required|string|max:255',
                'link' => 'required|url',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal. Silakan periksa kembali input Anda.', $validator->errors());
            }

            $data->update($request->all());

            return new GlobalResource(true, 'Data Panorama berhasil diperbarui.', $data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return new GlobalResource(false, 'Link CCTV sudah digunakan. Harap gunakan link yang berbeda.', null);
            }

            return new GlobalResource(false, 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan tak terduga. Silakan coba beberapa saat lagi.', null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Panorama::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan.', null);
            }

            $data->delete();

            return new GlobalResource(true, 'Data Panorama berhasil dihapus.', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.', null);
        }
    }
}
