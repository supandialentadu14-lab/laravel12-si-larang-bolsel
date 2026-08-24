<?php

namespace App\Http\Controllers;

use App\Models\BtsTower;
use App\Models\BtsTowerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\BtsTowerPhoto;
use App\Models\BtsAlert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BtsTowersImport;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;

class BtsTowerController extends Controller
{
    public function index(Request $request)
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
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bts', 'like', "%{$search}%")
                  ->orWhere('kode_bts', 'like', "%{$search}%")
                  ->orWhere('desa', 'like', "%{$search}%");
            });
        }

        $towers = $query->latest()->paginate(10)->withQueryString();

        $mapPoints = BtsTower::query()->get(['id', 'kode_bts', 'nama_bts', 'provider', 'kecamatan', 'desa', 'latitude', 'longitude', 'status_operasional', 'kondisi', 'coverage_radius']);

        $statsQuery = BtsTower::query();
        if ($request->filled('kecamatan')) $statsQuery->where('kecamatan', $request->kecamatan);
        if ($request->filled('provider')) $statsQuery->where('provider', $request->provider);
        if ($request->filled('status_operasional')) $statsQuery->where('status_operasional', $request->status_operasional);

        $stats = [
            'total' => $statsQuery->count(),
            'aktif' => (clone $statsQuery)->where('status_operasional', 'Aktif')->count(),
            'maintenance' => (clone $statsQuery)->where('status_operasional', 'Maintenance')->count(),
            'tidak_aktif' => (clone $statsQuery)->where('status_operasional', 'Tidak Aktif')->count(),
        ];

        return view('bts-towers.index', [
            'towers' => $towers,
            'mapPoints' => $mapPoints,
            'kecamatanList' => BtsTower::$kecamatanList,
            'providerList' => BtsTower::$providerList,
            'statusList' => BtsTower::$statusList,
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return view('bts-towers.create', [
            'kecamatanList' => BtsTower::$kecamatanList,
            'providerList' => BtsTower::$providerList,
            'tipeTowerList' => BtsTower::$tipeTowerList,
            'kondisiList' => BtsTower::$kondisiList,
            'statusList' => BtsTower::$statusList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('bts-towers', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['kode_bts'] = $this->generateKodeBts();

        $tower = BtsTower::create($validated);

        $this->logActivity('create', $tower, null, $validated);

        if (($validated['status_operasional'] ?? null) === 'Tidak Aktif') {
            BtsAlert::create([
                'bts_tower_id' => BtsTower::latest()->first()->id,
                'user_id' => Auth::id(),
                'type' => 'status_changed',
                'title' => 'BTS Tidak Aktif: ' . $validated['nama_bts'],
                'message' => 'BTS baru "' . $validated['nama_bts'] . '" (' . $validated['kode_bts'] . ') tercatat dalam status Tidak Aktif.',
            ]);
        }

        return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil ditambahkan dengan kode ' . $validated['kode_bts'] . '.');
    }

    /**
     * Buat kode BTS otomatis, format: BTS-BOLSEL-{tahun}-{urut 3 digit}
     * Nomor urut reset setiap tahun berdasarkan jumlah data yang sudah dibuat tahun berjalan.
     */
    private function generateKodeBts(): string
    {
        $year = now()->year;
        $count = BtsTower::whereYear('created_at', $year)->count() + 1;

        do {
            $kode = 'BTS-BOLSEL-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            $exists = BtsTower::where('kode_bts', $kode)->exists();
            $count++;
        } while ($exists);

        return $kode;
    }

    public function show(BtsTower $btsTower)
    {
        $btsTower->load(['notes' => function ($q) { $q->latest(); }]);

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
            ->take(10)
            ->values();

        $providerColors = [
            'Telkomsel' => '#e74c3c',
            'Indosat' => '#f39c12',
            'XL Axiata' => '#3498db',
            'Tri (3)' => '#9b59b6',
            'Smartfren' => '#2ecc71',
            'Lainnya' => '#95a5a6',
        ];

        return view('bts-towers.show', compact('btsTower', 'nearbyTowers', 'providerColors'));
    }

    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function edit(BtsTower $btsTower)
    {
        return view('bts-towers.edit', [
            'btsTower' => $btsTower,
            'kecamatanList' => BtsTower::$kecamatanList,
            'providerList' => BtsTower::$providerList,
            'tipeTowerList' => BtsTower::$tipeTowerList,
            'kondisiList' => BtsTower::$kondisiList,
            'statusList' => BtsTower::$statusList,
        ]);
    }

    public function update(Request $request, BtsTower $btsTower)
    {
        $oldData = $btsTower->toArray();
        $validated = $this->validateData($request, $btsTower->id);

        if ($request->hasFile('foto')) {
            if ($btsTower->foto) {
                Storage::disk('public')->delete($btsTower->foto);
            }
            $validated['foto'] = $request->file('foto')->store('bts-towers', 'public');
        }

        $btsTower->update($validated);
        $this->logActivity('update', $btsTower, $oldData, $btsTower->fresh()->toArray());

        return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil diperbarui.');
    }

    public function destroy(BtsTower $btsTower)
    {
        $this->logActivity('delete', $btsTower, $btsTower->toArray(), null);
        if ($btsTower->foto) {
            Storage::disk('public')->delete($btsTower->foto);
        }
        $btsTower->delete();

        return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil dihapus.');
    }

    public function reportPdf(BtsTower $btsTower)
    {
        $mapImage = $this->renderOsmStaticMap(
            (float) $btsTower->latitude,
            (float) $btsTower->longitude,
            16,
            600,
            400,
            [['lat' => (float) $btsTower->latitude, 'lng' => (float) $btsTower->longitude]],
            1
        );

        $pdf = Pdf::loadView('bts-towers.pdf', [
            'btsTower' => $btsTower,
            'mapImage' => $mapImage,
        ])->setPaper('a4', 'portrait');

        $response = $pdf->stream('laporan-bts-' . Str::slug($btsTower->kode_bts) . '.pdf');

        return $response;
    }

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

        // Urutkan kronologis sesuai kapan data dibuat, lalu beri nomor urut berjalan
        $towers = $query->orderBy('created_at')->get();

        // Load photos relationship untuk semua tower
        $towers->load('photos');

        $towers = $towers->values()->map(function ($t, $i) {
            $t->no_urut = $i + 1;
            return $t;
        });

        // Urutan kecamatan kustom
        $kecamatanOrder = [
            'Pinolosian Timur',
            'Pinolosian Tengah',
            'Pinolosian',
            'Bolaang Uki',
            'Helumo',
            'Tomini',
            'Posigadan',
        ];

        // Kelompokkan per kecamatan dengan urutan kustom
        $grouped = $towers->groupBy('kecamatan');
        $towersByKecamatan = $grouped->sortBy(function ($items, $key) use ($kecamatanOrder) {
            $index = array_search($key, $kecamatanOrder);
            return $index !== false ? $index : 999;
        });

        // Siapkan foto base64 untuk setiap tower (untuk PDF)
        $towerPhotos = $towers->mapWithKeys(function ($t) {
            $base64 = $this->getFirstPhotoBase64($t);
            return [$t->id => $base64];
        });

        $rekapStatus = $towers->filter(fn($t) => $t->status_operasional)->groupBy('status_operasional')->map->count();
        $rekapKondisi = $towers->filter(fn($t) => $t->kondisi)->groupBy('kondisi')->map->count();
        $rekapProvider = $towers->filter(fn($t) => $t->provider)->groupBy('provider')->map->count();

        // Generate peta static dari semua BTS
        $mapImage = null;
        if ($towers->isNotEmpty()) {
            [$centerLat, $centerLng, $zoom] = $this->calculateMapCenterAndZoom($towers);
            $markerPoints = $towers->filter(fn($t) => $t->latitude && $t->longitude)
                ->map(fn($t) => ['lat' => (float) $t->latitude, 'lng' => (float) $t->longitude])
                ->values()
                ->toArray();
            $mapImage = $this->renderOsmStaticMap($centerLat, $centerLng, $zoom, 600, 400, $markerPoints, 2);
        }

        $pdf = Pdf::loadView('bts-towers.pdf-all', [
            'towers' => $towers,
            'towersByKecamatan' => $towersByKecamatan,
            'towerPhotos' => $towerPhotos,
            'rekapStatus' => $rekapStatus,
            'rekapKondisi' => $rekapKondisi,
            'rekapProvider' => $rekapProvider,
            'mapImage' => $mapImage,
            'filterInfo' => [
                'kecamatan' => $request->kecamatan,
                'provider' => $request->provider,
                'status_operasional' => $request->status_operasional,
            ],
        ])->setPaper('a4', 'landscape');

        $response = $pdf->stream('laporan-bts-kabupaten-bolsel-' . now()->format('Ymd_His') . '.pdf');

        return $response;
    }

    /**
     * Hitung titik tengah & level zoom yang pas supaya semua titik BTS tercakup di peta.
     */
    private function calculateMapCenterAndZoom($towers): array
    {
        if ($towers->isEmpty()) {
            return [0.4317, 123.4817, 10];
        }

        $lats = $towers->pluck('latitude')->map(fn ($v) => (float) $v);
        $lngs = $towers->pluck('longitude')->map(fn ($v) => (float) $v);

        $centerLat = ($lats->max() + $lats->min()) / 2;
        $centerLng = ($lngs->max() + $lngs->min()) / 2;

        $latSpan = max($lats->max() - $lats->min(), 0.01);
        $lngSpan = max($lngs->max() - $lngs->min(), 0.01);
        $maxSpan = max($latSpan, $lngSpan);

        $zoom = match (true) {
            $maxSpan > 1.2 => 9,
            $maxSpan > 0.6 => 10,
            $maxSpan > 0.3 => 11,
            $maxSpan > 0.15 => 12,
            $maxSpan > 0.07 => 13,
            default => 14,
        };

        return [$centerLat, $centerLng, $zoom];
    }

    /**
     * Ubah koordinat lat/lon jadi posisi tile (format "slippy map"), dipakai untuk
     * menghitung tile mana saja yang perlu diunduh dan posisi piksel marker.
     */
    private function latLngToTileXY(float $lat, float $lng, int $zoom): array
    {
        $latRad = deg2rad($lat);
        $n = 2 ** $zoom;
        $x = ($lng + 180) / 360 * $n;
        $y = (1 - log(tan($latRad) + 1 / cos($latRad)) / M_PI) / 2 * $n;

        return [$x, $y];
    }

    /**
     * Ambil satu tile peta, dengan cache lokal di storage/app/map-tiles supaya
     * laporan berikutnya di area yang sama jauh lebih cepat (tidak download ulang).
     */
    private function fetchTileCached(int $zoom, int $x, int $y, $context): ?string
    {
        $cacheDir = storage_path("app/map-tiles/{$zoom}/{$x}");
        $cacheFile = "{$cacheDir}/{$y}.png";

        if (file_exists($cacheFile)) {
            $data = @file_get_contents($cacheFile);
            if ($data !== false && strlen($data) > 100) {
                return $data;
            }
        }

        $url = "https://tile.openstreetmap.org/{$zoom}/{$x}/{$y}.png";
        $data = @file_get_contents($url, false, $context);

        if ($data === false || strlen($data) < 100) {
            return null;
        }

        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        @file_put_contents($cacheFile, $data);

        return $data;
    }

    /**
     * Susun sendiri gambar peta dari tile resmi OpenStreetMap (tile.openstreetmap.org),
     * lalu gambar titik marker di atasnya memakai GD. Hasilnya di-encode base64 JPEG
     * supaya ukuran kecil dan bisa langsung ditempel ke PDF.
     */
    private function renderOsmStaticMap(
        float $centerLat,
        float $centerLng,
        int $zoom,
        int $outWidth,
        int $outHeight,
        array $markerPoints = [],
        int $tileRadius = 2
    ): ?string {
        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        if (function_exists('set_time_limit')) {
            @set_time_limit(120);
        }

        try {
            [$centerXf, $centerYf] = $this->latLngToTileXY($centerLat, $centerLng, $zoom);
            $originTileX = (int) floor($centerXf) - $tileRadius;
            $originTileY = (int) floor($centerYf) - $tileRadius;
            $gridSize = ($tileRadius * 2 + 1);
            $canvasSize = $gridSize * 256;
            $maxTile = (2 ** $zoom) - 1;

            $canvas = imagecreatetruecolor($canvasSize, $canvasSize);
            $bg = imagecolorallocate($canvas, 226, 223, 216);
            imagefill($canvas, 0, 0, $bg);

            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: BOLSEL-DIGITAL-BTS-App/1.0 (kontak: supandialentadu14@gmail.com)\r\n",
                    'timeout' => 5,
                ],
            ]);

            $anyTileLoaded = false;

            for ($tx = 0; $tx < $gridSize; $tx++) {
                for ($ty = 0; $ty < $gridSize; $ty++) {
                    $tileX = $originTileX + $tx;
                    $tileY = $originTileY + $ty;

                    if ($tileX < 0 || $tileY < 0 || $tileX > $maxTile || $tileY > $maxTile) {
                        continue;
                    }

                    $data = $this->fetchTileCached($zoom, $tileX, $tileY, $context);

                    if ($data === null) {
                        continue;
                    }

                    $tileImg = @imagecreatefromstring($data);
                    if (!$tileImg) {
                        continue;
                    }

                    imagecopy($canvas, $tileImg, $tx * 256, $ty * 256, 0, 0, 256, 256);
                    imagedestroy($tileImg);
                    $anyTileLoaded = true;
                }
            }

            if (!$anyTileLoaded) {
                imagedestroy($canvas);
                return null;
            }

            $red = imagecolorallocate($canvas, 220, 38, 38);
            $white = imagecolorallocate($canvas, 255, 255, 255);
            $darkRed = imagecolorallocate($canvas, 153, 0, 0);

            $markerPixelPositions = [];

            foreach ($markerPoints as $point) {
                [$px, $py] = $this->latLngToTileXY($point['lat'], $point['lng'], $zoom);
                $px = ($px - $originTileX) * 256;
                $py = ($py - $originTileY) * 256;

                $ipx = (int) round($px);
                $ipy = (int) round($py);

                if ($ipx < -30 || $ipx > $canvasSize + 30 || $ipy < -30 || $ipy > $canvasSize + 30) {
                    continue;
                }

                $markerPixelPositions[] = ['x' => $px, 'y' => $py];
            }

            if (!empty($markerPixelPositions)) {
                $minX = min(array_column($markerPixelPositions, 'x'));
                $maxX = max(array_column($markerPixelPositions, 'x'));
                $minY = min(array_column($markerPixelPositions, 'y'));
                $maxY = max(array_column($markerPixelPositions, 'y'));

                $cropCenterX = ($minX + $maxX) / 2;
                $cropCenterY = ($minY + $maxY) / 2;

                $cropLeft = (int) round($cropCenterX - $outWidth / 2);
                $cropTop = (int) round($cropCenterY - $outHeight / 2);
            } else {
                $centerPx = ($centerXf - $originTileX) * 256;
                $centerPy = ($centerYf - $originTileY) * 256;
                $cropLeft = (int) round($centerPx - $outWidth / 2);
                $cropTop = (int) round($centerPy - $outHeight / 2);
            }

            $cropLeft = max(0, min($cropLeft, $canvasSize - $outWidth));
            $cropTop = max(0, min($cropTop, $canvasSize - $outHeight));

            $final = imagecreatetruecolor($outWidth, $outHeight);
            imagecopy($final, $canvas, 0, 0, $cropLeft, $cropTop, $outWidth, $outHeight);
            imagedestroy($canvas);

            $fRed = imagecolorallocate($final, 220, 38, 38);
            $fWhite = imagecolorallocate($final, 255, 255, 255);
            $fDarkRed = imagecolorallocate($final, 153, 0, 0);
            $fBlack = imagecolorallocate($final, 255, 255, 255);

            foreach ($markerPixelPositions as $mp) {
                $fx = (int) round($mp['x'] - $cropLeft);
                $fy = (int) round($mp['y'] - $cropTop);

                imagefilledellipse($final, $fx, $fy, 30, 30, $fDarkRed);
                imagefilledellipse($final, $fx, $fy, 24, 24, $fRed);
                imageellipse($final, $fx, $fy, 24, 24, $fWhite);
                imagefilledellipse($final, $fx, $fy, 8, 8, $fWhite);
            }

            $textColor = imagecolorallocate($final, 60, 60, 60);
            $bgText = imagecolorallocate($final, 255, 255, 255);
            imagefilledrectangle($final, 0, $outHeight - 14, 195, $outHeight, $bgText);
            imagestring($final, 2, 3, $outHeight - 13, '(c) OpenStreetMap contributors', $textColor);

            ob_start();
            imagejpeg($final, null, 85);
            $imageData = ob_get_clean();

            imagedestroy($final);

            return 'data:image/jpeg;base64,' . base64_encode($imageData);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function toggleStatus(BtsTower $btsTower)
    {
        $oldStatus = $btsTower->status_operasional;
        $statuses = BtsTower::$statusList;
        $current = array_search($btsTower->status_operasional, $statuses);
        $next = $current !== false ? ($current + 1) % count($statuses) : 0;
        $btsTower->update(['status_operasional' => $statuses[$next]]);

        $this->logActivity('status_toggle', $btsTower, ['status_operasional' => $oldStatus], ['status_operasional' => $statuses[$next]]);

        if ($statuses[$next] === 'Tidak Aktif') {
            BtsAlert::create([
                'bts_tower_id' => $btsTower->id,
                'user_id' => Auth::id(),
                'type' => 'status_changed',
                'title' => 'BTS Tidak Aktif: ' . $btsTower->nama_bts,
                'message' => 'Status BTS "' . $btsTower->nama_bts . '" (' . $btsTower->kode_bts . ') diubah ke Tidak Aktif oleh ' . Auth::user()->name . '.',
            ]);
        }

        return back()->with('success', 'Status "' . $btsTower->kode_bts . '" diubah ke ' . $statuses[$next] . '.');
    }

    public function addNote(Request $request, BtsTower $btsTower)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:catatan,perawatan,kerusakan,inspeksi'],
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'tanggal' => ['nullable', 'date'],
            'biaya' => ['nullable', 'string', 'max:20'],
            'teknisi' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['bts_tower_id'] = $btsTower->id;
        $validated['user_id'] = Auth::id();
        $validated['tanggal'] = $validated['tanggal'] ?? now()->toDateString();

        BtsTowerNote::create($validated);

        return back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function destroyNote(BtsTowerNote $note)
    {
        $towerId = $note->bts_tower_id;
        $note->delete();

        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'tower_ids' => ['required', 'array'],
            'tower_ids.*' => ['exists:bts_towers,id'],
        ]);

        $towers = BtsTower::whereIn('id', $request->tower_ids)->get();

        foreach ($towers as $tower) {
            if ($tower->foto) {
                Storage::disk('public')->delete($tower->foto);
            }
            $tower->delete();
        }

        return back()->with('success', count($towers) . ' data BTS berhasil dihapus.');
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

        // Urutan kecamatan kustom (sama dengan PDF)
        $kecamatanOrder = [
            'Pinolosian Timur', 'Pinolosian Tengah', 'Pinolosian',
            'Bolaang Uki', 'Helumo', 'Tomini', 'Posigadan',
        ];

        $towers = $query->orderBy('created_at')->get();
        $towers = $towers->values()->map(function ($t, $i) {
            $t->no_urut = $i + 1;
            return $t;
        });

        // Kelompokkan per kecamatan dengan urutan kustom
        $grouped = $towers->groupBy('kecamatan');
        $sorted = $grouped->sortBy(function ($items, $key) use ($kecamatanOrder) {
            $index = array_search($key, $kecamatanOrder);
            return $index !== false ? $index : 999;
        });

        $filename = 'laporan-bts-kabupaten-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sorted) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Desa', 'Titik Koordinat', 'Provider', 'Nama Perusahaan']);

            foreach ($sorted as $kecamatan => $items) {
                foreach ($items as $t) {
                    fputcsv($file, [
                        $t->no_urut,
                        $t->desa ?: '-',
                        $t->latitude . ', ' . $t->longitude,
                        $t->provider ?: '-',
                        $t->nama_perusahaan ?? '-',
                    ]);
                }
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
            'file' => ['required', 'file', 'max:5120'],
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            return back()->withErrors(['file' => 'Format file tidak didukung. Gunakan CSV, XLS, atau XLSX.']);
        }

        try {
            DB::beginTransaction();
            Excel::import(new BtsTowersImport, $file);
            DB::commit();

            return redirect()->route('bts-towers.index')->with('success', 'Data BTS berhasil diimport.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function addPhotos(Request $request, BtsTower $btsTower)
    {
        $request->validate([
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['image', 'max:2048'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($request->file('photos', []) as $index => $photo) {
            $path = $photo->store('bts-towers/photos', 'public');
            $caption = $request->captions[$index] ?? null;

            BtsTowerPhoto::create([
                'bts_tower_id' => $btsTower->id,
                'user_id' => Auth::id(),
                'path' => $path,
                'caption' => $caption,
                'sort_order' => $index,
            ]);
        }

        return back()->with('success', count($request->file('photos')) . ' foto berhasil ditambahkan.');
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
            'coverage_radius' => ['nullable', 'numeric', 'min:0', 'max:50'],
        ]);

        $btsTower->update(['coverage_radius' => $request->coverage_radius]);

        return back()->with('success', 'Radius cakupan diperbarui.');
    }

    public function exportGeojson(Request $request)
    {
        $query = BtsTower::query();
        if ($request->filled('kecamatan')) $query->where('kecamatan', $request->kecamatan);
        if ($request->filled('provider')) $query->where('provider', $request->provider);

        $towers = $query->get();

        $features = $towers->map(function ($t) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$t->longitude, (float)$t->latitude],
                ],
                'properties' => [
                    'id' => $t->id,
                    'kode_bts' => $t->kode_bts,
                    'nama_bts' => $t->nama_bts,
                    'provider' => $t->provider,
                    'kecamatan' => $t->kecamatan,
                    'desa' => $t->desa,
                    'status' => $t->status_operasional,
                    'kondisi' => $t->kondisi,
                    'coverage_radius' => $t->coverage_radius,
                ],
            ];
        });

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features->toArray(),
        ];

        $filename = 'bts-bolsel-' . now()->format('Ymd_His') . '.geojson';

        return response()->json($geojson)
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Content-Type', 'application/geo+json');
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
        $kml .= '<name>BTS Bolaang Mongondow Selatan</name>' . "\n";
        $kml .= '<description>Data Sebaran BTS Kab. Bolsel</description>' . "\n";

        $providerColors = [
            'Telkomsel' => 'ff0000ff', 'Indosat' => 'ff00ccff', 'XL Axiata' => 'ffdb4d00',
            'Tri (3)' => 'ff9b59b6', 'Smartfren' => 'ff00cc66', 'Lainnya' => 'ff808080',
        ];

        foreach ($towers as $t) {
            $color = $providerColors[$t->provider] ?? 'ff808080';
            $desc = "Kode: {$t->kode_bts}\nProvider: {$t->provider}\nKecamatan: {$t->kecamatan}\nStatus: {$t->status_operasional}";
            $kml .= '<Placemark>' . "\n";
            $kml .= '<name>' . htmlspecialchars($t->nama_bts) . '</name>' . "\n";
            $kml .= '<description><![CDATA[' . nl2br($desc) . ']]></description>' . "\n";
            $kml .= '<Style><IconStyle><color>' . $color . '</color></IconStyle></Style>' . "\n";
            $kml .= '<Point><coordinates>' . $t->longitude . ',' . $t->latitude . ',0</coordinates></Point>' . "\n";
            $kml .= '</Placemark>' . "\n";
        }

        $kml .= '</Document>' . "\n";
        $kml .= '</kml>';

        $filename = 'bts-bolsel-' . now()->format('Ymd_His') . '.kml';

        return response($kml, 200)
            ->header('Content-Type', 'application/vnd.google-earth.kml+xml')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function compare(Request $request)
    {
        $ids = $request->input('tower_ids', []);
        if (count($ids) < 2) {
            return redirect()->route('bts-towers.index')->with('error', 'Pilih minimal 2 BTS untuk dibandingkan.');
        }
        if (count($ids) > 5) {
            return redirect()->route('bts-towers.index')->with('error', 'Maksimal 5 BTS untuk dibandingkan.');
        }

        $towers = BtsTower::whereIn('id', $ids)->get();

        $providerColors = [
            'Telkomsel' => '#e74c3c', 'Indosat' => '#f39c12', 'XL Axiata' => '#3498db',
            'Tri (3)' => '#9b59b6', 'Smartfren' => '#2ecc71', 'Lainnya' => '#95a5a6',
        ];

        return view('bts-towers.compare', compact('towers', 'providerColors'));
    }

    public function alerts()
    {
        $alerts = BtsAlert::with('tower')->latest()->paginate(20);
        return view('bts-towers.alerts', compact('alerts'));
    }

    public function markAlertRead(BtsAlert $alert)
    {
        $alert->update(['is_read' => true]);
        return back();
    }

    public function markAllAlertsRead()
    {
        BtsAlert::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroyAlert(BtsAlert $alert)
    {
        $alert->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'nama_bts' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'in:' . implode(',', BtsTower::$providerList)],
            'nama_perusahaan' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['required', 'in:' . implode(',', BtsTower::$kecamatanList)],
            'desa' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'tinggi_tower' => ['nullable', 'numeric', 'min:0'],
            'tipe_tower' => ['nullable', 'string', 'max:100'],
            'kondisi' => ['nullable', 'in:' . implode(',', BtsTower::$kondisiList)],
            'status_operasional' => ['nullable', 'in:' . implode(',', BtsTower::$statusList)],
            'tahun_dibangun' => ['nullable', 'digits:4', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'foto' => ['nullable', 'image', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
            'coverage_radius' => ['nullable', 'numeric', 'min:0', 'max:50'],
        ]);

        // Kosongkan (null) field opsional kalau dikirim string kosong,
        // supaya tersimpan rapi sebagai "belum diisi", bukan string kosong.
        foreach (['tipe_tower', 'kondisi', 'status_operasional'] as $field) {
            if (($validated[$field] ?? null) === '') {
                $validated[$field] = null;
            }
        }

        if (($validated['coverage_radius'] ?? null) === '') {
            $validated['coverage_radius'] = null;
        }

        return $validated;
    }

    private function getFirstPhotoBase64(BtsTower $tower): ?string
    {
        $paths = [];

        if ($tower->foto) {
            $paths[] = storage_path('app/public/' . $tower->foto);
        }

        $photo = $tower->photos()->first();
        if ($photo && $photo->path) {
            $paths[] = storage_path('app/public/' . $photo->path);
        }

        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                $mime = mime_content_type($path);
                if ($mime && in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                    $data = base64_encode(file_get_contents($path));
                    return 'data:' . $mime . ';base64,' . $data;
                }
            }
        }

        return null;
    }

    private function logActivity(string $action, BtsTower $tower, ?array $old, ?array $new): void
    {
        try {
            $fieldLabels = [
                'kode_bts' => 'Kode BTS', 'nama_bts' => 'Nama BTS', 'provider' => 'Provider',
                'nama_perusahaan' => 'Nama Perusahaan',
                'kecamatan' => 'Kecamatan', 'desa' => 'Desa', 'alamat' => 'Alamat',
                'latitude' => 'Latitude', 'longitude' => 'Longitude',
                'tinggi_tower' => 'Tinggi Tower', 'tipe_tower' => 'Tipe Tower',
                'kondisi' => 'Kondisi', 'status_operasional' => 'Status Operasional',
                'tahun_dibangun' => 'Tahun Dibangun', 'keterangan' => 'Keterangan',
                'coverage_radius' => 'Radius Cakupan', 'foto' => 'Foto',
            ];

            $filteredOld = $old ? array_intersect_key($old, $fieldLabels) : null;
            $filteredNew = $new ? array_intersect_key($new, $fieldLabels) : null;

            $desc = match($action) {
                'create' => 'Menambahkan BTS baru: ' . $tower->nama_bts . ' (' . $tower->kode_bts . ')',
                'update' => 'Mengubah data BTS: ' . $tower->nama_bts,
                'delete' => 'Menghapus BTS: ' . $tower->nama_bts . ' (' . $tower->kode_bts . ')',
                'status_toggle' => 'Status BTS "' . $tower->nama_bts . '" diubah',
                default => 'Aksi pada BTS: ' . $tower->nama_bts,
            };

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => BtsTower::class,
                'model_id' => $tower->id,
                'description' => $desc,
                'properties' => [
                    'old' => $filteredOld,
                    'new' => $filteredNew,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Silent fail — audit log should never break main flow
        }
    }
}
