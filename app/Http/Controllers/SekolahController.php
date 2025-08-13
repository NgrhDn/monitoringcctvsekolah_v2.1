<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cctv;
use App\Models\Sekolah;
use App\Models\Wilayah;
use App\Models\Panorama;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SekolahExport;
use Illuminate\Support\Str; // ADD: slug helper

class SekolahController extends Controller
{
    public function dashboard()
    {
        // Hitung total jumlah CCTV dari tabel cctvs yang terhubung dengan sekolah
        // Ini akan memberikan total semua CCTV sekolah, bukan hanya jumlah sekolah.
        $sekolahCount = Cctv::whereNotNull('sekolah_id')->count();

        // Hitung total jumlah panorama
        $panoramaCount = Panorama::count();

        // Hitung total jumlah user
        $userCount = User::count();

        // Statistik jumlah sekolah per wilayah
        $jumlahSekolahPerWilayah = Sekolah::select('wilayah.nama_wilayah as namaWilayah', DB::raw('COUNT(sekolah.id) as total_sekolah'))
            ->join('wilayah', 'sekolah.wilayah_id', '=', 'wilayah.id')
            ->groupBy('wilayah.nama_wilayah')
            ->get();

        // Statistik jumlah CCTV per wilayah
        $jumlahCCTVPerWilayah = Cctv::select('wilayah.nama_wilayah as namaWilayah', DB::raw('COUNT(cctvs.link_stream) as total_cctv'))
            ->join('wilayah', 'cctvs.wilayah_id', '=', 'wilayah.id')
            ->whereNotNull('cctvs.link_stream')
            ->groupBy('wilayah.nama_wilayah')
            ->get();

        // Statistik jumlah CCTV per sekolah
        $jumlahCCTVPerSekolah = Cctv::select('sekolah.nama_sekolah as namaSekolah', DB::raw('COUNT(cctvs.link_stream) as total_cctv'))
            ->join('sekolah', 'cctvs.sekolah_id', '=', 'sekolah.id')
            ->whereNotNull('cctvs.link_stream')
            ->groupBy('sekolah.nama_sekolah')
            ->get();

        return view('admin.dashboard', compact(
            'sekolahCount', 'panoramaCount', 'userCount',
            'jumlahSekolahPerWilayah', 'jumlahCCTVPerWilayah', 'jumlahCCTVPerSekolah'
        ));
    }

    public function cctvsekolah()
    {
        // Ambil CCTV aktif saja + eager load relasi, dan hanya kolom yang dibutuhkan
        $cctvs = Cctv::with(['sekolah', 'wilayah'])
            ->where('active', true)
            ->select('id', 'sekolah_id', 'wilayah_id', 'nama_titik', 'link_stream', 'active')
            ->orderBy('wilayah_id')->orderBy('sekolah_id')->orderBy('nama_titik')->get();

        $groupedCctvs = $cctvs
            ->sortBy(fn($c) => [$c->wilayah->nama_wilayah, $c->sekolah->nama_sekolah, $c->nama_titik])
            ->groupBy(fn($c) => $c->wilayah->nama_wilayah)
            ->sortKeys() // urutkan wilayah
            ->map(function ($wg) {
                return $wg->groupBy(fn($c) => $c->sekolah->nama_sekolah)
                        ->sortKeys() // urutkan sekolah
                        ->map(fn($sg) => $sg->sortBy('nama_titik')); // urutkan titik CCTV
            });

        // Statistik ringkas dan efisien
        $jumlahCCTV = Cctv::count();
        $jumlahSekolah = Sekolah::count();
        $jumlahWilayah = Wilayah::count();
        $jumlahCCTVaktif = Cctv::where('active', true)->count();

        // Mapping label wilayah (dipakai di blade)
        $namaWilayahLengkap = [
            'KABUPATEN GK'  => 'KABUPATEN GUNUNG KIDUL',
            'KABUPATEN KP'  => 'KABUPATEN KULONPROGO',
            'KABUPATEN BTL' => 'KABUPATEN BANTUL',
            'KABUPATEN SLM' => 'KABUPATEN SLEMAN',
            'KOTA YK'       => 'KOTA YOGYAKARTA',
        ];

        // Build lightweight index for lazy render on the client
        $cctvIndex = $cctvs->map(function ($c) {
            return [
                'wilayah'     => $c->wilayah->nama_wilayah,
                'sekolah'     => $c->sekolah->nama_sekolah,
                'sekolahSlug' => Str::slug($c->sekolah->nama_sekolah),
                'titik'       => $c->nama_titik,
                'link'        => $c->link_stream,
                'active'      => (bool) $c->active,
                'cardId'      => Str::slug($c->sekolah->nama_sekolah . '-' . $c->nama_titik),
            ];
        })->groupBy('sekolahSlug'); // sekolahSlug => [items...]

        return view('sekolah.sekolah', compact(
            'groupedCctvs',
            'jumlahCCTV',
            'jumlahSekolah',
            'jumlahWilayah',
            'jumlahCCTVaktif',
            'namaWilayahLengkap',
            'cctvIndex' // ADD
        ));
    }

    public function bulkToggle(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'active' => 'required|boolean',
        ]);

        Cctv::whereIn('id', $request->ids)
            ->update(['active' => $request->active]);

        return response()->json([
            'success' => true,
            'message' => 'Status semua CCTV berhasil diperbarui.',
        ]);
    }

    public function index()
    {
        $cctvs = Cctv::with(['sekolah', 'wilayah'])->paginate(10);
        return view('sekolah.menu-sekolah', compact('cctvs'));
    }

    public function create()
    {
        $sekolahs = Sekolah::all();
        $wilayahs = Wilayah::all();
        return view('sekolah.create', compact('sekolahs', 'wilayahs'));
    }

    public function store(Request $request)
    {
        $cctv = new Cctv;
        $cctv->sekolah_id = $request->sekolah_id;
        $cctv->wilayah_id = $request->wilayah_id;
        $cctv->nama_titik = $request->namaTitik;
        $cctv->link_stream = $request->link;
        $cctv->active = true;
        $cctv->save();
        return redirect()->route('sekolah.index');
    }

    public function edit($id)
    {
        $cctv = Cctv::with(['sekolah', 'wilayah'])->findOrFail($id);
        $sekolahs = Sekolah::all();
        $wilayahs = Wilayah::all();
        return view('sekolah.edit', compact('cctv', 'sekolahs', 'wilayahs'));
    }

    public function update(Request $request, $id)
    {
        $cctv = Cctv::findOrFail($id);
        $cctv->sekolah_id = $request->sekolah_id;
        $cctv->wilayah_id = $request->wilayah_id;
        $cctv->nama_titik = $request->namaTitik;
        $cctv->link_stream = $request->link;
        $cctv->save();
        return redirect()->route('sekolah.index');
    }

    public function delete($id)
    {
        $cctv = Cctv::findOrFail($id);
        $cctv->delete();
        return redirect()->route('sekolah.index');
    }

    public function checkDuplicate(Request $request)
    {
        $field = $request->get('field');
        $value = $request->get('value');
        $exists = Cctv::where($field === 'namaTitik' ? 'nama_titik' : 'link_stream', $value)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getWilayah()
    {
        $wilayahs = Wilayah::select('nama_wilayah')->distinct()->get();
        return response()->json($wilayahs);
    }

    public function export()
    {
        return Excel::download(new SekolahExport, 'data-cctv-sekolah.xlsx');
    }

    public function showRekapanCCTV()
    {
        $data = DB::table('cctvs')
            ->join('sekolah', 'cctvs.sekolah_id', '=', 'sekolah.id')
            ->join('wilayah', 'cctvs.wilayah_id', '=', 'wilayah.id')
            ->select(
                'sekolah.nama_sekolah as namaSekolah',
                'wilayah.nama_wilayah as namaWilayah',
                DB::raw('COUNT(cctvs.id) as total_cctv')
            )
            ->groupBy('sekolah.nama_sekolah', 'wilayah.nama_wilayah')
            ->orderBy('sekolah.nama_sekolah', 'asc')
            ->get();

        return view('rekapan.cctv_sekolah', ['jumlahCCTVPerSekolah' => $data]);
    }

    public function daftarSekolah()
    {
        $sekolah = Sekolah::with('wilayah')->get();
        return view('rekapan.detailsekolah', compact('sekolah'));
    }


    public function toggle($id)
    {
        $cctv = Cctv::findOrFail($id);
        $cctv->active = !$cctv->active;
        $cctv->save();

        return response()->json([
            'success' => true,
            'message' => 'Status CCTV berhasil diubah.',
            'data' => $cctv
        ]);
    }
}
