<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panorama;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PanoramaExport;
use Illuminate\Support\Facades\DB;



class PanoramaController extends Controller
{
    public function dashboard()
    {
        $panorama = Panorama::all();
        return view('panorama.panorama', compact('panorama'));
    }

    public function index()
    {
        $panorama = panorama::paginate(10);
        return view('panorama.menu-panorama', compact('panorama'));
    }

    public function create()
    {
        return view('panorama.create');
    }

    public function store(Request $request)
    {
        $panorama = new Panorama;
        $panorama->namaWilayah = $request->namaWilayah;
        $panorama->namaTitik = $request->namaTitik;
        $panorama->link = $request->link;
        $panorama->save();
        return redirect()->route('panorama.index');
    }

    public function edit($id)
    {
        $panorama = Panorama::find($id);
        return view('panorama.edit', compact('panorama'));
    }

    public function update(Request $request, $id)
    {
        $panorama = Panorama::find($id);
        $panorama->namaWilayah = $request->namaWilayah;
        $panorama->namaTitik = $request->namaTitik;
        $panorama->link = $request->link;
        $panorama->save();
        return redirect()->route('panorama.index');
    }

    public function delete($id)
    {
        $panorama = Panorama::find($id);
        $panorama->delete();
        return redirect()->route('panorama.index')->with('success', 'CCTV Panorama berhasil Dihapus.');
    }

    public function getWilayah()
    {
        $panorama = Panorama::select('namaWilayah')->distinct()->get(); // Ambil hanya kolom namaWilayah
        return response()->json($panorama);
    }

    public function export()
    {

        return Excel::download(new PanoramaExport, 'data-cctv-panorama.xlsx');
    }

    public function showPanorama()
    {
        $rekap = DB::table('panorama')
            ->select('namaWilayah', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('namaWilayah')
            ->get();

        return view('rekapan.cctv_panorama', compact('rekap'));
    }

    public function detailWilayah($wilayah)
    {
        $dataPanorama = DB::table('panorama')
            ->where('namaWilayah', $wilayah)
            ->get();

        return view('rekapan.detail_cctv_panorama', compact('dataPanorama', 'wilayah'));
    }

}

?>