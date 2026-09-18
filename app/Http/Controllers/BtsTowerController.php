<?php

namespace App\Http\Controllers;

use App\Models\BtsTower;
use App\Models\BtsTowerNote;
use App\Models\BtsTowerPhoto;
use App\Models\BtsAlert;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Illuminate\Http\Response;

class BtsTowerController extends Controller
{
    public function index()
    {
        $search = request('search');
        $dateFilter = request('date');

        $query = BtsTower::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_bts', 'like', "%{$search}%")
                  ->orWhere('kode_bts', 'like', "%{$search}%")
                  ->orWhere('desa', 'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%");
            });
        }

        if (request('kecamatan')) {
            $query->where('kecamatan', request('kecamatan'));
        }

        if (request('provider')) {
            $query->where('provider', request('provider'));
        }

        if (request('status_operasional')) {
            $query->where('status_operasional', request('status_operasional'));
        }

        $towers = $query->orderBy('created_at', 'desc')->paginate(10);

        $btsTotal = BtsTower::count();
        $btsAktif = BtsTower::where('status_operasional', 'Aktif')->count();
        $btsMaintenance = BtsTower::where('status_operasional', 'Maintenance')->count();
        $btsTidakAktif = BtsTower::where('status_operasional', 'Tidak Aktif')->count();

        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('bts-towers.index', compact(
            'towers',
            'opdSetting',
            'singkatanOpd',
            'btsTotal',
            'btsAktif',
            'btsMaintenance',
            'btsTidakAktif'
        ));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('bts-towers.create', compact('products', 'singkatanOpd'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'kode_bts' => 'required|string|max:50|unique:bts_towers,kode_bts',
            'nama_bts' => 'required|string|max:255',
            'provider' => 'required|in:' . implode(',', BtsTower::$providerList),
            'kecamatan' => 'required|in:' . implode(',', BtsTower::$kecamatanList),
            'desa' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'tinggi_tower' => 'nullable|numeric|min:0',
            'tipe_tower' => 'nullable|in:' . implode(',', BtsTower::$tipeTowerList),
            'kondisi' => 'nullable|in:' . implode(',', BtsTower::$kondisiList),
            'status_operasional' => 'nullable|in:' . implode(',', BtsTower::$statusList),
            'tahun_dibangun' => 'nullable|digits:4|integer|min:1990|max:' . (date('Y') + 1),
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
            'coverage_radius' => 'nullable|numeric|min:0|max:50',
        ]);

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('bts-towers', 'public');
        }

        $data['user_id'] = auth()->id();

        $tower = BtsTower::create($data);

        return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil ditambahkan.');
    }

    public function show(BtsTower $btsTower)
    {
        $btsTower->load(['notes' => function ($q) { $q->latest(); }, 'photos', 'alerts']);

        $nearbyTowers = BtsTower::where('id', '!=', $btsTower->id)
            ->select('id', 'kode_bts', 'nama_bts', 'provider', 'kecamatan', 'latitude', 'longitude', 'status_operasional')
            ->get()
            ->map(function ($t) use ($btsTower) {
                $t->distance = $this->haversineDistance(
                    (float) $btsTower->latitude, (float) $btsTower->longitude,
                    (float) $t->latitude, (float) $t->longitude
                );
                return $t;
            })
            ->sortBy('distance')
            ->take(5);

        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');
        $providerColors = [
            'Telkomsel' => '#e74c3c', 'Indosat' => '#f39c12', 'XL Axiata' => '#3498db',
            'Tri (3)' => '#9b59b6', 'Smartfren' => '#2ecc71', 'Lainnya' => '#95a5a6',
        ];

        return view('bts-towers.show', compact('btsTower', 'nearbyTowers', 'providerColors', 'opdSetting', 'singkatanOpd'));
    }

    public function edit(BtsTower $btsTower)
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('bts-towers.edit', compact('btsTower', 'products', 'singkatanOpd'));
    }

    public function update(Request $request, BtsTower $btsTower): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'sometimes|exists:products,id',
            'kode_bts' => 'sometimes|string|max:50|unique:bts_towers,kode_bts,' . $btsTower->id,
            'nama_bts' => 'sometimes|string|max:255',
            'provider' => 'sometimes|in:' . implode(',', BtsTower::$providerList),
            'kecamatan' => 'sometimes|in:' . implode(',', BtsTower::$kecamatanList),
            'desa' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'tinggi_tower' => 'nullable|numeric|min:0',
            'tipe_tower' => 'nullable|in:' . implode(',', BtsTower::$tipeTowerList),
            'kondisi' => 'nullable|in:' . implode(',', BtsTower::$kondisiList),
            'status_operasional' => 'nullable|in:' . implode(',', BtsTower::$statusList),
            'tahun_dibangun' => 'nullable|digits:4|integer|min:1990|max:' . (date('Y') + 1),
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
            'coverage_radius' => 'nullable|numeric|min:0|max:50',
        ]);

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            if ($btsTower->foto) {
                Storage::disk('public')->delete($btsTower->foto);
            }
            $data['foto'] = $request->file('foto')->store('bts-towers', 'public');
        }

        $btsTower->update($data);

        return redirect()->route('bts-towers.show', $btsTower)->with('success', 'Data BTS berhasil diperbarui.');
    }

    public function destroy(BtsTower $btsTower): RedirectResponse
    {
        if ($btsTower->foto) {
            Storage::disk('public')->delete($btsTower->foto);
        }

        $btsTower->delete();

        return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil dihapus.');
    }

    /**
     * Laporan PDF untuk satu tower
     */
    public function reportPdf(BtsTower $btsTower)
    {
        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');
        $providerColors = [
            'Telkomsel' => '#e74c3c', 'Indosat' => '#f39c12', 'XL Axiata' => '#3498db',
            'Tri (3)' => '#9b59b6', 'Smartfren' => '#2ecc71', 'Lainnya' => '#95a5a6',
        ];

        $pdf = Pdf::loadView('bts-towers.pdf', [
            'btsTower' => $btsTower,
            'providerColors' => $providerColors,
            'opdSetting' => $opdSetting,
            'singkatanOpd' => $singkatanOpd,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-bts-' . $btsTower->kode_bts . '-' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Laporan PDF kabupaten (semua tower)
     */
    public function reportPdfAll(Request $request)
    {
        $query = BtsTower::query();

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional', $request->status_operasional);
        }

        $towers = $query->orderBy('created_at')->get();

        // Nomor urut
        $towers = $towers->values()->map(function ($t, $i) {
            $t->no_urut = $i + 1;
            return $t;
        });

        // Group by kecamatan with custom order
        $kecamatanOrder = [
            'Pinolosian Timur',
            'Pinolosian Tengah',
            'Pinolosian',
            'Bolaang Uki',
            'Helumo',
            'Tomini',
            'Posigadan',
        ];

        $grouped = $towers->groupBy('kecamatan');
        $towersByKecamatan = $grouped->sortBy(function ($items, $key) use ($kecamatanOrder) {
            $index = array_search($key, $kecamatanOrder);
            return $index !== false ? $index : 999;
        });

        $rekapStatus = $towers->filter(fn($t) => $t->status_operasional)->groupBy('status_operasional')->map->count();
        $rekapKondisi = $towers->filter(fn($t) => $t->kondisi)->groupBy('kondisi')->map->count();
        $rekapProvider = $towers->filter(fn($t) => $t->provider)->groupBy('provider')->map->count();

        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        $pdf = Pdf::loadView('bts-towers.pdf-all', [
            'towers' => $towers,
            'towersByKecamatan' => $towersByKecamatan,
            'rekapStatus' => $rekapStatus,
            'rekapKondisi' => $rekapKondisi,
            'rekapProvider' => $rekapProvider,
            'filterInfo' => [
                'kecamatan' => $request->kecamatan,
                'provider' => $request->provider,
                'status_operasional' => $request->status_operasional,
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-bts-kabupaten-bolsel-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = BtsTower::query();

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }
        if ($request->filled('status_operasional')) {
            $query->where('status_operasional', $request->status_operasional);
        }

        $towers = $query->orderBy('created_at')->get();

        $filename = 'data-bts-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($towers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kode BTS', 'Nama BTS', 'Provider', 'Kecamatan', 'Desa', 'Latitude', 'Longitude', 'Tinggi', 'Tipe', 'Kondisi', 'Status', 'Tahun', 'Keterangan']);

            $no = 1;
            foreach ($towers as $t) {
                fputcsv($file, [
                    $no++,
                    $t->kode_bts,
                    $t->nama_bts,
                    $t->provider,
                    $t->kecamatan,
                    $t->desa ?? '-',
                    $t->latitude,
                    $t->longitude,
                    $t->tinggi_tower ?? '-',
                    $t->tipe_tower ?? '-',
                    $t->kondisi ?? '-',
                    $t->status_operasional ?? '-',
                    $t->tahun_dibangun ?? '-',
                    $t->keterangan ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importForm()
    {
        return view('bts-towers.import', [
            'kecamatanList' => BtsTower::$kecamatanList,
            'providerList' => BtsTower::$providerList,
        ]);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $imported = 0;
        $errors = [];

        try {
            if ($extension === 'csv') {
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 5) continue;
                    [$kode_bts, $nama_bts, $provider, $kecamatan, $desa, $latitude, $longitude] = $row;

                    BtsTower::updateOrCreate(
                        ['kode_bts' => trim($kode_bts)],
                        [
                            'nama_bts' => trim($nama_bts),
                            'provider' => trim($provider),
                            'kecamatan' => trim($kecamatan),
                            'desa' => trim($desa),
                            'latitude' => (float) $latitude,
                            'longitude' => (float) $longitude,
                            'user_id' => auth()->id(),
                        ]
                    );
                    $imported++;
                }
                fclose($handle);
            } else {
                // Handle Excel if needed
                $errors[] = 'Format file tidak didukung. Gunakan CSV.';
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        return redirect()->route('bts-towers.index')->with('success', "Berhasil import {$imported} data BTS.");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bts_towers,id',
        ]);

        $towers = BtsTower::whereIn('id', $request->ids)->get();

        foreach ($towers as $tower) {
            if ($tower->foto) {
                Storage::disk('public')->delete($tower->foto);
            }
            $tower->delete();
        }

        return redirect()->route('bts-towers.index')->with('success', count($request->ids) . ' data BTS berhasil dihapus.');
    }

    public function exportGeojson(Request $request)
    {
        $query = BtsTower::query();
        if ($request->filled('kecamatan')) $query->where('kecamatan', $request->kecamatan);
        if ($request->filled('provider')) $query->where('provider', $request->provider);

        $towers = $query->get();

        $features = [];
        foreach ($towers as $tower) {
            if ($tower->latitude && $tower->longitude) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float) $tower->longitude, (float) $tower->latitude],
                    ],
                    'properties' => [
                        'kode_bts' => $tower->kode_bts,
                        'nama_bts' => $tower->nama_bts,
                        'provider' => $tower->provider,
                        'kecamatan' => $tower->kecamatan,
                        'desa' => $tower->desa,
                        'status' => $tower->status_operasional,
                    ],
                ];
            }
        }

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        return response()->json($geojson, 200, [], JSON_PRETTY_PRINT)
            ->header('Content-Disposition', 'attachment; filename="bts-towers-' . now()->format('Ymd') . '.geojson"');
    }

    public function exportKml(Request $request)
    {
        $query = BtsTower::query();
        if ($request->filled('kecamatan')) $query->where('kecamatan', $request->kecamatan);
        if ($request->filled('provider')) $query->where('provider', $request->provider);

        $towers = $query->get();

        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2">' . "\n";
        $kml .= '<Document>' . "\n";
        $kml .= '<name>BTS Towers</name>' . "\n";

        foreach ($towers as $tower) {
            if (!$tower->latitude || !$tower->longitude) continue;
            $kml .= '<Placemark>' . "\n";
            $kml .= '  <name>' . htmlspecialchars($tower->nama_bts) . '</name>' . "\n";
            $kml .= '  <description>' . "\n";
            $kml .= '    <![CDATA[' . "\n";
            $kml .= '      Kode: ' . htmlspecialchars($tower->kode_bts) . '<br/>' . "\n";
            $kml .= '      Provider: ' . htmlspecialchars($tower->provider) . '<br/>' . "\n";
            $kml .= '      Kecamatan: ' . htmlspecialchars($tower->kecamatan) . '<br/>' . "\n";
            $kml .= '      Desa: ' . htmlspecialchars($tower->desa ?? '-') . '<br/>' . "\n";
            $kml .= '      Status: ' . htmlspecialchars($tower->status_operasional ?? '-') . '<br/>' . "\n";
            $kml .= '    ]]>' . "\n";
            $kml .= '  </description>' . "\n";
            $kml .= '  <Point>' . "\n";
            $kml .= '    <coordinates>' . $tower->longitude . ',' . $tower->latitude . ',0</coordinates>' . "\n";
            $kml .= '  </Point>' . "\n";
            $kml .= '</Placemark>' . "\n";
        }

        $kml .= '</Document>' . "\n";
        $kml .= '</kml>';

        return response($kml, 200, [
            'Content-Type' => 'application/vnd.google-earth.kml+xml',
            'Content-Disposition' => 'attachment; filename="bts-towers-' . now()->format('Ymd') . '.kml"',
        ]);
    }

    public function compare(Request $request)
    {
        $ids = $request->input('tower_ids', []);
        $towers = BtsTower::whereIn('id', $ids)->with('photos')->get();

        return view('bts-towers.compare', compact('towers'));
    }

    public function alerts()
    {
        $alerts = BtsAlert::with('btsTower')->latest()->paginate(20);
        return view('bts-towers.alerts', compact('alerts'));
    }

    public function toggleStatus(BtsTower $btsTower)
    {
        $statuses = ['Aktif', 'Maintenance', 'Tidak Aktif'];
        $currentIndex = array_search($btsTower->status_operasional, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $btsTower->update(['status_operasional' => $statuses[$nextIndex]]);

        if ($btsTower->status_operasional === 'Tidak Aktif') {
            BtsAlert::create([
                'bts_tower_id' => $btsTower->id,
                'user_id' => auth()->id(),
                'type' => 'status_changed',
                'title' => 'BTS Tidak Aktif: ' . $btsTower->nama_bts,
                'message' => 'BTS "' . $btsTower->nama_bts . '" (' . $btsTower->kode_bts . ') kini berstatus Tidak Aktif.',
            ]);
        }

        return back()->with('success', 'Status BTS berhasil diubah.');
    }

    public function addNote(Request $request, BtsTower $btsTower)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        BtsTowerNote::create([
            'bts_tower_id' => $btsTower->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function destroyNote(BtsTowerNote $note)
    {
        $note->delete();
        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    public function addPhotos(Request $request, BtsTower $btsTower)
    {
        $request->validate([
            'photos.*' => 'required|image|max:2048',
        ]);

        $files = $request->file('photos');
        foreach ($files as $file) {
            $path = $file->store('bts-towers', 'public');
            BtsTowerPhoto::create([
                'bts_tower_id' => $btsTower->id,
                'user_id' => auth()->id(),
                'path' => $path,
            ]);
        }

        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    public function deletePhoto(BtsTowerPhoto $photo)
    {
        if ($photo->path) {
            Storage::disk('public')->delete($photo->path);
        }
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function updateCoverage(Request $request, BtsTower $btsTower)
    {
        $request->validate([
            'coverage_radius' => 'nullable|numeric|min:0|max:50',
        ]);

        $btsTower->update(['coverage_radius' => $request->coverage_radius]);

        return back()->with('success', 'Radius cakupan berhasil diperbarui.');
    }

    public function markAlertRead(BtsAlert $alert)
    {
        $alert->update(['is_read' => true]);
        return back();
    }

    public function markAllAlertsRead()
    {
        BtsAlert::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroyAlert(BtsAlert $alert)
    {
        $alert->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }
}