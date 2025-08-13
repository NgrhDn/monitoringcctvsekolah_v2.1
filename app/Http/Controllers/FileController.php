<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function destroy($filename)
    {
        // Pastikan file berada di dalam folder 'public' atau 'storage/app/public'
        $filePath = 'public/uploads/' . $filename;

        // Cek apakah file ada
        if (Storage::exists($filePath)) {
            // Hapus file
            Storage::delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File not found.'
            ], 404);
        }
    }
}